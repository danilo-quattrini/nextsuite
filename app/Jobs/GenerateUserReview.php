<?php

namespace App\Jobs;

use App\Domain\User\Services\UserService;
use App\Services\OpenAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateUserReview implements ShouldQueue
{
    use Queueable;

    private UserService $userService;

    /**
     * Create a new job instance.
     */
    public function __construct(
        UserService $userService
    ) 
    {
        $this->userService = $userService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $openAIService = new OpenAIService();
        $result = $openAIService->generateReview($this->buildReviewData());
        $this->userService->setReview($result);
    }

    /**
     * Collect all relevant user data for the AI review
     * */
    private function buildReviewData(): array
    {
        $skills = $this->userService->getSkills();
        $ratings = $this->userService->getRatings();
        return [
            'ratings' => $ratings,
            'skills' => $skills
        ];
    }
}
