<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\DocumentRequest;
use App\Services\OpenAIService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class GenerateCustomerDocument implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300; // 5 minutes timeout
    public int $tries = 3; // Retry 3 times if it fails


    protected Customer $customer;
    protected string $documentType;
    protected int $requestId;

    /**
     * Create a new job instance.
     */
    public function __construct(Customer $customer, string $documentType, int $requestId)
    {
        $this->customer = $customer;
        $this->documentType = $documentType;
        $this->requestId = $requestId;
    }

    /**
     * Execute the job.
     */
    public function handle(OpenAIService $openAIService): void
    {
        $documentRequest = DocumentRequest::find($this->requestId);

        try {
            list($customer, $skills, $attributes) = $this->customer_info($this->customer);
            $skill_names = $skills->pluck('name')->all();
            $skillSeparated = implode(', ', $skill_names);
            $summary = $openAIService->generateCustomerSummary([
                'full_name' => $this->customer->full_name,
                'years' => 5,
                'skills' => $skillSeparated
            ]);

            $pdf = Pdf::loadView('documents.template-1', compact('customer', 'skills', 'attributes', 'summary'));
            $filename = "documents/{$this->customer->id}_{$this->documentType}_".time().".pdf";
            Storage::put($filename, $pdf->output());

            $this->customer->update([
                'document_path' => $filename,
                'document_generated_at' => now()
            ]);

            $documentRequest->update([
                'status' => 'completed',
                'document_url' => Storage::url($filename),
                'completed_at' => now(),
            ]);

        } catch (\Exception $e) {
            $documentRequest->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function customer_info(Customer $customer): array
    {
        $skills = $customer->skills->map(function ($skill) {
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'description' => $skill->description,
                'years' => $skill->pivot->years ?? 0,
                'level' => $skill->pivot->level ?? null,
            ];
        });

        $attributes = $customer->attributes->map(function ($attribute){
            return [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'value' => $attribute->pivot->value ?? null
            ];
        });
        return array($customer, $skills, $attributes);
    }

    public function failed(\Throwable $exception): void
    {
        DocumentRequest::find($this->requestId)->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}
