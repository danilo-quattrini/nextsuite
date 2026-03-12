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
     * @param  AttributeAssignable  $model
     * @param  int  $id
     * @throws Exception
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

    /**
     * Edit an attribute value from an AttributeAssignable model.
     * @param  AttributeAssignable  $model
     * @param  Attribute  $attribute
     * @param  mixed  $value
     * @throws Exception
     */
    public function edit(
        AttributeAssignable $model,
        Attribute $attribute,
        mixed $value
    ): void {
        try {
            $model->attributes()->updateExistingPivot($attribute->id, ['value' => $value]);
        } catch (Exception $e) {
            Log::error('Failed to edit the attribute', [
                'model' => get_class($model),
                'attribute_id' => $attribute->id,
                'error' => $e->getMessage(),
            ]);
            throw $e; // re-throw so Livewire can handle it
        }
    }

    /**
     * Replace an attribute assignment entirely (category + attribute + value).
     * @param  AttributeAssignable  $model
     * @param  int  $oldAttributeId
     * @param  Attribute  $newAttribute
     * @param  mixed  $newValue
     * @throws Exception
     */
    public function replace(
        AttributeAssignable $model,
        int $oldAttributeId,
        Attribute $newAttribute,
        mixed $newValue
    ): void {
        try {
            $model->attributes()->detach($oldAttributeId);
            $model->addAttribute($newAttribute, $newValue);
        } catch (Exception $e) {
            Log::error('Failed to replace attribute', [
                'model'           => get_class($model),
                'old_attribute_id' => $oldAttributeId,
                'new_attribute_id' => $newAttribute->id,
                'error'           => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}