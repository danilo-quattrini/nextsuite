<?php

namespace App\Domain\Attribute\Contracts;

use App\Models\Attribute;

interface AttributeAssignable
{
    public function addAttribute(Attribute $attribute, mixed $value): void;
}