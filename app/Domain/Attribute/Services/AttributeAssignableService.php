<?php

namespace App\Domain\Attribute\Services;

use App\Domain\Attribute\Contracts\AttributeAssignable;
use App\Models\Attribute;

class AttributeAssignableService
{
    public function assign(
        AttributeAssignable $model,
        Attribute $attribute,
        mixed $value,
    ): void {
       $model->addAttribute($attribute, $value);
    }
}