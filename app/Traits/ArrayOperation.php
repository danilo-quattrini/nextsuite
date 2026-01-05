<?php

namespace App\Traits;

trait ArrayOperation
{
    /**
     * Remove an item from a form array property by its key.
     *
     * @param string $property The name of the array property on the form object.
     * @param int|string $key The key of the item to remove.
     */
    public function removeItem(string $property, int|string $key): void
    {
        if (isset($this->form->{$property}) && is_array($this->form->{$property})) {
            if (array_key_exists($key, $this->form->{$property})) {
                unset($this->form->{$property}[$key]);
            }
        }
    }

}
