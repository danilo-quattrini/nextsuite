<?php

namespace App\Traits;

trait ArrayOperation
{
    /**
     * Add an item from a form array property by its key from a form.
     *
     * @param string $targetProperty The name of the array that we want to add an obj.
     * @param int|string $key The key of the item to remove.
     * @param  string  $sourceProperty  the name of the item to add.
     * @param  string  $sourceKey  the key of the item to add.
     */
    public function toggleItem(
        string $targetProperty,
        int|string $key,
        string $sourceProperty,
        string $sourceKey = 'id'
    ): void {
        if (!isset($this->{$targetProperty}) || !is_array($this->{$targetProperty})) {
            $this->{$targetProperty} = [];
        }

        if (isset($this->{$targetProperty}[$key])) {
            unset($this->{$targetProperty}[$key]);
            return;
        }

        if (!isset($this->{$sourceProperty})) {
            return;
        }

        $item = $this->{$sourceProperty}->firstWhere($sourceKey, $key);

        if ($item) {
            $this->{$targetProperty}[$key] = $item;
        }
    }

    /**
     * Check if an item is inside the array though its key and remove it.
     *
     * @param array $array The array to check and remove the item from it.
     * @param int|string $key The key of the item to remove.
     */
    protected function removeFromArray(array &$array, int|string $key): void
    {
        if (array_key_exists($key, $array)) {
            unset($array[$key]);
        }
    }

    /**
     * Remove an item from a form array property by its key from a form.
     *
     * @param string $property The name of the array property on the form object.
     * @param int|string $key The key of the item to remove.
     */
    public function removeItem(string $property, int|string $key): void
    {
        if (isset($this->form->{$property}) && is_array($this->form->{$property})) {
            $this->removeFromArray($this->form->{$property}, $key);
        }
    }

    /**
     * Remove an item from a form array property by its key.
     *
     * @param string $property The name of the array property on the form object.
     * @param int|string $key The key of the item to remove.
     */
    public function removeArrayItem(string $property, int|string $key): void
    {
        if (isset($this->{$property}) && is_array($this->{$property})) {
            $this->removeFromArray($this->{$property}, $key);
        }
    }

}
