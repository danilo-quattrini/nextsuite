<?php

namespace App\Jobs;

use App\Models\DocumentRequest;
use App\Services\OpenAIService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class GenerateDocument implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected ?Model $subject = null;
    protected ?string $type = null;
    protected ?int $requestId = null;
    /**
     * Create a new job instance.
     */
    public function __construct(Model $subject, string $type, int $requestId)
    {
        $this->subject = $subject;
        $this->type = $type;
        $this->requestId = $requestId;
    }

    /**
     * Execute the job.
     * @throws \Exception if document generation fail
     */
    public function handle(OpenAIService $openAIService): void
    {

        $documentRequest = DocumentRequest::find($this->requestId);
        if (!$documentRequest) {
            throw new \RuntimeException("DocumentRequest {$this->requestId} not found.");
        }


        try {
            list($subject, $skills, $attributes) = $this->getSubjectInfo($this->subject);

            $summary = "Random summary";
            //$this->subjectSummary($subject, $skills, $openAIService);
            $pdf = Pdf::loadView('documents.template-1', compact('subject', 'skills', 'attributes', 'summary'));

            $fileName = "documents/{$this->subject->id}_{$this->type}_".time().".pdf";
            Storage::put($fileName, $pdf->output());



            $model = $this->getSubjectClass($subject);

            $document = $this->assignDocumentToModel($model, $documentRequest, $fileName);

            $this->completeRequest($document, $documentRequest, $fileName);

        } catch (\Exception $e) {
            $documentRequest->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            throw  $e;
        }

    }

    private function getSubjectClass(?Model $subject)
    {
        $class = get_class($subject);
        return $class::findOrFail($subject->id);
    }

    private function getSubjectInfo(?Model $subject): array
    {
        $skills = $this->getSubjectSkills($subject);
        $attributes = $this->getSubjectAttributes($subject);

        return array($subject, $skills, $attributes);
    }

    private function getSubjectSkills(?Model $subject): array
    {
        if (!$subject) {
            return [];
        }

        return $subject->skills->map(function($skill){
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'description' => $skill->description,
                'years' => $skill->pivot->years ?? 0,
                'level' => $skill->pivot->level ?? null,
            ];
        })->all();
    }

    private function getSubjectAttributes(?Model $subject): array
    {
        if (!$subject) {
            return [];
        }

        return $subject->attributes->map(function($attribute) {
                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'value' => $attribute->pivot->value ?? null
                ];
            }
        )->all();
    }

    private function assignDocumentToModel($model, $documentRequest, $fileName)
    {
        return $model->documents()->create([
            'name' =>  "{$model->id}_{$this->type}_".time().".pdf",
            'type' => $documentRequest->type,
            'file_path' =>  $fileName
        ]);
    }

    private function completeRequest($document, DocumentRequest|array|null $documentRequest, string $fileName ):void
    {
        $documentRequest->update([
            'status' => 'completed',
            'document_url' => Storage::url($fileName),
            'document_id' => $document->id,
            'completed_at' => now(),
        ]);
    }

    private function subjectSummary($subject, $skills, OpenAIService $openAIService): string
    {
        $skill_names = Arr::pluck($skills, 'name');
        $skillSeparated = implode(', ', $skill_names);


        return $openAIService->generateCustomerSummary([
            'full_name' => $subject?->full_name,
            'years' => 5,
            'skills' => $skillSeparated
        ]);
    }
}
