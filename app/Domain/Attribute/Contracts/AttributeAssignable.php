<?php

namespace App\Domain\Attribute\Contracts;

use App\Models\Attribute;
use Illuminate\Support\Collection;

interface AttributeAssignable
{
    public function addAttribute(Attribute $attribute, mixed $value): void;
    public function getAssignableAttribute(int $key): ?Attribute;
    public function getAssignableAttributes(): Collection;
}