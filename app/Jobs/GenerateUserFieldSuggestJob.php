<?php

namespace App\Jobs;

use App\Domain\User\Services\UserService;
use App\Services\OpenAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateUserFieldSuggestJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 300; // Ollama is slower than OpenAI
    public int $backoff = 5;  // wait 10s between retries


    public function __construct(
        private readonly UserService  $userService
    ) {}

    /**
     * Execute the job.
     * Instance a new OpenAIService, call the operation to generate the field suggestion
     * from the build data that has been collected in the userService.
     * The result of the generation will be set in the variable field of the UserService class.
     */
    public function handle(): void
    {
        $openAIService = new OpenAIService();
        $result = $openAIService->generateFieldSuggestion($this->buildFieldSuggestionData());
        echo $result;
        $this->userService->setField($result);
    }

    /**
     * Collect all relevant user data for the OpenAI review
     * */
    private function buildFieldSuggestionData(): array
    {
        $skills = $this->userService->getSkills();
        $attributes = $this->userService->getAttributes();
        $ratings = $this->userService->getRatings();
        return [
            'ratings' => $ratings,
            'skills' => $skills,
            'attributes' => $attributes
        ];
    }
}
