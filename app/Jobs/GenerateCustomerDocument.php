<?php

namespace App\Jobs;

use App\Models\Customer;
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

    /**
     * Create a new job instance.
     */
    public function __construct(Customer $customer, string $documentType)
    {
        $this->customer = $customer;
        $this->documentType = $documentType;
    }

    /**
     * Execute the job.
     */
    public function handle(OpenAIService $openAIService): void
    {
        list($customer, $skills, $attributes) = $this->customer_info($this->customer);

        $skill_names = $skills->pluck('name')->all();
        $skillSeparated = implode(', ', $skill_names);

        $summary = $openAIService->generateCustomerSummary([
            'full_name' => $this->customer->full_name,
            'years' => 5,
            'skills' => $skillSeparated
        ]);

        $pdf = Pdf::loadView('documents.template-1', compact('customer', 'skills', 'attributes', 'summary'));

        $filename = "documents/{$this->customer->id}_{$this->documentType}_". time() . ".pdf";
        Storage::put($filename, $pdf->output());

        $this->customer->update([
            'document_path' => $filename,
            'document_generated_at' => now()
        ]);
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
        // Handle job failure - log it, notify admin, etc.
        \Log::error('Document generation failed', [
            'customer_id' => $this->customer->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
