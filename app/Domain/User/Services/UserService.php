<?php

namespace App\Domain\User\Services;

use App\Domain\Attribute\Contracts\AttributeAssignable;
use App\Domain\Skill\Contracts\SkillAssignable;
use App\Jobs\GenerateUserFieldSuggestJob;
use App\Jobs\GenerateUserReview;
use Illuminate\Support\Facades\Cache;

class UserService
{
    private const int REVIEW_TTL_SECONDS = 21600;
    private const int REVIEW_LOCK_TTL_SECONDS = 600;

    private const int FIELD_TTL_SECONDS = 21600;

    public function __construct(
        private readonly SkillAssignable | AttributeAssignable $user,
        private ?string $review = null,
        private ?string $field = null
    ) {}

    // ====== GENERATION METHODS ======

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
     * Get the review made from the OpenAI service in base of user info
     * */
    public function generateUserFieldSuggest(): void
    {
        if (!$this->hasSkill() && $this->hasUser()) {
            return;
        }

        GenerateUserFieldSuggestJob::dispatch($this);
    }


    // ====== GETTERS ======

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
     * Get the field suggestion of the user if he/she has it.
     *
     * Get from the cache the field if exists, then it will
     * check, if the cache contains the field, it will return
     * the value from the cache and assign it to the field attribute,
     * otherwise it will return an empty string.
     *
     * @return  string generated field suggestion string
     * */
    public function getField(): string
    {
        if ($this->field) {
            return $this->field;
        }

        $cached = Cache::get($this->fieldCacheKey());

        if (is_string($cached) && $cached !== '') {
            $this->field = $cached;
            return $cached;
        }
        return '';
    }

    // ====== SETTERS ======

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
     * Get the review of the user if he/she has it
     *
     * Save the field in the attribute field and save it
     * inside the cache.
     *
     * @param  string  $field to save
     * @return self
     */
    public function setField(string $field): self
    {
        $this->field = $field;
        Cache::put($this->fieldCacheKey(), $field,  self::FIELD_TTL_SECONDS);
        return $this;
    }

    // ====== HELPER METHODS ======

    /**
     * Check if the user relation with skill exists
     * */
    private function hasSkill(): bool
    {
        return $this->user->hasSkill();
    }

    /**
     * Check if the user it's been initialized or not
     * */
    private function hasUser(): bool
    {
        return $this->user !== null;
    }

    // ====== CACHE KEY GENERATOR METHODS ======

    /**
     * Compose a string that will be the key for the cache of the review generated
     * @return string a string that indicates the key of the cache
     * */
    private function reviewCacheKey(): string
    {
        list($class, $id) = $this->getMorphClassAndId();

        return "user-review:{$class}:{$id}";
    }

    /**
     * Compose a string that will be the key for the cache of the field suggestion generated
     * @return string a string that indicates the key of the cache
     * */
    private function fieldCacheKey(): string
    {
        list($class, $id) = $this->getMorphClassAndId();

        return "user-field:{$class}:{$id}";
    }

    /**
     * Function to return the class the id from the user.
     * @return array with the class and the id of the user.
     * */
    public function getMorphClassAndId(): array
    {
        $class = method_exists($this->getUser(), 'getMorphClass')
            ? $this->getUser()->getMorphClass()
            : get_class($this->getUser());

        $id = method_exists($this->getUser(), 'getKey')
            ? (string) $this->getUser()->getKey()
            : 'unknown';
        return array($class, $id);
    }


}
