<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class OpenAIService
{
    /**
     * Generate a short professional summary for a customer
     */
    public function generateCustomerSummary(array $data): string
    {
        $prompt = $this->buildSummaryPrompt($data);

        $response = $this->openAICall('You write short professional summaries for documents.', $prompt);

        return trim($response->choices[0]->message->content);
    }

    /**
     * Build a safe, deterministic prompt
     */
    protected function buildSummaryPrompt(array $data): string
    {
        return <<<TEXT
                Write a concise professional summary.
                
                Customer information:
                - Name: {$data['full_name']}
                - Years of experience: {$data['years']}
                - Main skills: {$data['skills']}
                
                The tone must be neutral and suitable for a formal document that will be generated.
                TEXT;
    }
    /**
     * Call the OpenAI API to work with a content and a prompt
     */
    protected function openAICall(
        ?string $content = null,
        ?string $prompt = null
    ): CreateResponse
    {
        return OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $content,
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.3,
        ]);
}
