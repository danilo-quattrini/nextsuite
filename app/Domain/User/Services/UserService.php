<?php

namespace App\Domain\User\Services;

use App\Domain\Attribute\Contracts\AttributeAssignable;
use App\Domain\Skill\Contracts\SkillAssignable;
use App\Jobs\GenerateUserReview;
use Illuminate\Support\Facades\Cache;

class UserService
{
    private const int REVIEW_TTL_SECONDS = 21600;
    private const int REVIEW_LOCK_TTL_SECONDS = 600;

    public function __construct(
        private readonly SkillAssignable | AttributeAssignable $user,
        private ?string $review = null,
        private ?string $field = null
    ) {}

    /**
     * Get the review made from the OpenAI service in base of user info
     * */
    public function generateUserReview(): void
    {
        if (!$this->hasSkill() && $this->hasUser()) {
            return;
        }

        $cacheKey = $this->reviewCacheKey();
        if (Cache::has($cacheKey)) {
            $this->review = (string) Cache::get($cacheKey);
            return;
        }

        $lockKey = "{$cacheKey}:generating";
        if (!Cache::add($lockKey, true, self::REVIEW_LOCK_TTL_SECONDS)) {
            return;
        }

        GenerateUserReview::dispatch($this);
    }


    /**
     * Get all the SkillAssignable model skills into an array
     * */
    public function getSkills(): array
    {
        return   $this->getUser()->getSkills()->toArray();
    }

    /**
     * Get all the AttributeAssignable model attributes into an array
     * */
    public function getAttributes(): array
    {
        return   $this->getUser()->getAssignableAttributes()->toArray();
    }

    /**
     * Get an array of all the rating and comment from SkillAssignable user.
     * */
    public function getRatings(): array
    {
        return $this->getUser()->reviews()->exists() ? $this->getUser()->reviews()->get()->toArray() : [];
    }

    /**
     * Get the User model
     * */
    public function getUser(): SkillAssignable | AttributeAssignable
    {
        return $this->user;
    }

    /**
     * Check if the user it's been initialized or not
     * */
    private function hasUser(): bool
    {
        return $this->user !== null;
    }

    /**
     * Get the review of the user if he/she has it
     * */
    public function getReview(): string
    {
        if ($this->review) {
            return $this->review;
        }

        $cached = Cache::get($this->reviewCacheKey());
        if (is_string($cached) && $cached !== '') {
            $this->review = $cached;
            return $cached;
        }

        return '';
    }

    /**
     * Get the review of the user if he/she has it
     * */
    public function setReview(string $review): self
    {
        $this->review = $review;
        Cache::put($this->reviewCacheKey(), $review, self::REVIEW_TTL_SECONDS);
        Cache::forget($this->reviewCacheKey().':generating');
        return $this;
    }
    /**
     * Check if the user relation with skill exists
     * */
    private function hasSkill(): bool
    {
        return $this->user->hasSkill();
    }

    private function reviewCacheKey(): string
    {
        $class = method_exists($this->user, 'getMorphClass')
            ? $this->user->getMorphClass()
            : get_class($this->user);

        $id = method_exists($this->user, 'getKey')
            ? (string) $this->user->getKey()
            : 'unknown';

        return "user-review:{$class}:{$id}";
    }
}
