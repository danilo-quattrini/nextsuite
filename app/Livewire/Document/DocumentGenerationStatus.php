<?php

namespace App\Livewire\Document;

use App\Models\DocumentRequest;
use Livewire\Component;

class DocumentGenerationStatus extends Component
{
    public $requestId;
    public $status = 'processing';
    public $documentUrl = null;
    public $errorMessage = null;
    public $progress = 0;
    public $showModalDocumentGenerationStatus = true;

    // Stop polling when document is ready or failed
    public function getListeners(): array
    {
        return [];
    }

    public function mount($requestId): void
    {
        $this->requestId = $requestId;
        $this->checkStatus();
    }

    public function checkStatus(): void
    {
        $documentRequest = DocumentRequest::find($this->requestId);

        if (!$documentRequest) {
            $this->status = 'failed';
            $this->errorMessage = 'Document request not found';
            return;
        }

        $this->status = $documentRequest->status;
        $this->documentUrl = $documentRequest->document_url;
        $this->errorMessage = $documentRequest->error_message;

        // Update progress indicator
        $this->progress = $this->calculateProgress($documentRequest);

        // Dispatch browser event when completed
        if ($this->status === 'completed') {
            $this->dispatch('document-ready', url: $this->documentUrl);
        } elseif ($this->status === 'failed') {
            $this->dispatch('document-failed', error: $this->errorMessage);
        }
    }

    private function calculateProgress($documentRequest): int
    {
        return match ($documentRequest->status) {
            'processing' => $this->calculateProcessingProgress($documentRequest),

            'completed' => 100,

            default => 0,
        };
    }

    private function calculateProcessingProgress($documentRequest): int
    {
        $startedAt = $documentRequest->started_at ?? $documentRequest->created_at;

        $elapsedSeconds = now()->diffInSeconds($startedAt);

        $expectedDuration = 30;

        $progress = (int) round(($elapsedSeconds / $expectedDuration) * 80);

        return max(5, min(95, $progress));
    }

    public function downloadDocument()
    {
        if ($this->status === 'completed' && $this->documentUrl) {
            return redirect($this->documentUrl);
        }
    }

    public function render()
    {
        return view('livewire.document.document-generation-status');
    }

}
