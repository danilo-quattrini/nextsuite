<?php

namespace App\Domain\User\Services;

use App\Domain\Skill\Contracts\SkillAssignable;
use App\Services\OpenAIService;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    public function __construct(
        private readonly ?SkillAssignable $user = null
    ) {}

    /**
     * Get the review made from the OpenAI service in base of user info
     * */
    public function getUserReview(): string
    {

        if($this->isNotEmpty()) {
            $openAIService = new OpenAIService();
            return $openAIService->generateReview($this->getUser()?->full_name);
        }else{
            return 'No, customer has been assigned!';
        }
    }

    /**
     * Get the User model
     * */
    public function getUser(): ?Model
    {
        return $this->user;
    }

    /**
     * Check if the user it's not empty
     * */
    private function isNotEmpty(): bool
    {
        return !empty($this->user);
    }

    /**
     * Check if the user relation with skill exists
     * */
    private function hasSkill(): bool
    {
        return $this->user->hasSkill();
    }

}