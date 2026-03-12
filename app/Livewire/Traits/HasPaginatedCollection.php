<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;


trait HasPaginatedCollection
{

    public int $visibleCount = 6;
    public int $initialVisible = 6;
    public int $incrementBy = 6;

    // Override this in your component to point to the right collection
    abstract protected function getPaginatedCollection(): Collection;

    /**
     * Take a certain number of items in the collection
     * @return Collection
     */
    #[Computed]
    public function visibleItems(): Collection
    {
        return $this->getPaginatedCollection()->take($this->visibleCount);
    }

    /**
     * Check if there are more items in the collection
     * @return bool
     */
    #[Computed]
    public function hasMore(): bool
    {
        return $this->getPaginatedCollection()->count() > $this->visibleCount;
    }

    /**
     * Counter to show how many hardSkill remain to show
     * @return int
     */
    #[Computed]
    public function remainingCount(): int
    {
        return max(0, $this->getPaginatedCollection()->count() - $this->visibleCount);
    }

    /**
     * Check if it can hide all the skill, that means it has been show all the hardSkill
     * @return bool
     */
    #[Computed]
    public function canShowLess(): bool
    {
        return $this->visibleCount > $this->initialVisible
            && $this->getPaginatedCollection()->count() > 1;
    }

    /**
     * Show more skills
     */
    public function showMore(): void
    {
        $remaining = $this->remainingCount;

        if ($remaining > 0) {
            $this->visibleCount += min($this->incrementBy, $remaining);
        }
    }

    /**
     * Show fewer items
     */
    public function showLess(): void
    {
        $this->visibleCount = $this->initialVisible;
    }

    /**
     * Show all items at once
     */
    public function showAll(): void
    {
        $this->visibleCount = $this->getPaginatedCollection()->count();
    }
}
