<?php

namespace App\Domain\Attribute\Services;

use App\Domain\Attribute\Contracts\AttributeAssignable;
use App\Models\Attribute;
use Exception;
use Illuminate\Support\Facades\Log;

class AttributeAssignableService
{
    /**
     * Assign an attribute from an AttributeAssignable model.
     */

    public function assign(
        AttributeAssignable $model,
        Attribute $attribute,
        mixed $value,
    ): void {
       $model->addAttribute($attribute, $value);
    }

    /**
     * Remove an attribute from an AttributeAssignable model.
     */
    public function remove(
        AttributeAssignable $model,
        int $id,
    ): void {
        try {
            $model->attributes()->detach($id);
        } catch (Exception $e) {
            Log::error('Failed to remove attribute', [
                'model' => get_class($model),
                'attribute_id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e; // re-throw so Livewire can handle it
        }
    }
}