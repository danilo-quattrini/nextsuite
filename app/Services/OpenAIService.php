<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class OpenAIService
{
    /**
     * Generate a review for a customer
     */
    public function generateReview(?array $data = []): string
    {
        $prompt = $this->buildReviewPrompt($data);

        $response = $this->openAICall('You write a review for a customer show web page.', $prompt);

        return trim($response->choices[0]->message->content);
    }

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
     * Build a safe, deterministic prompt
     */
    protected function buildReviewPrompt(?array $data = []): string
    {
        $skills = collect($data['skills'])
            ->map(fn($s) => "  - {$s['name']}: {$s['level']} out of 100, type: {$s['type']}")
            ->join("\n");

        return <<<TEXT
                Write a concise, neutral professional review for the following customer.
                Customer information:
                
                - Name: {$data['full_name']}
                
                Skills:
                {$skills}
                
                Instructions:
                - Keep the tone neutral and professional.
                - Highlight the customer's strongest areas.
                - Point out areas that could be improved.
                - Suggest which professional fields or roles this person would be best suited for.
                - Keep the response under 150 words.
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
}
