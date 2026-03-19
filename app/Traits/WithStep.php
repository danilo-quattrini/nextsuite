<?php

namespace App\Traits;

trait WithStep
{

    public int $step = 1;

    /**
     * Abstract Method where we define an array of
     * rule, the key of the array it's the attribute to
     * validate, the values are an array or a
     * string of rule, defined for the key.
     *
     * @return array of rules;
     * */
    abstract protected function stepRules(): array;

    /**
     * Method where we define an array of messages
     * where the key it's the attributeName.validationRule
     * and the value it's the message to show if the validation
     * it's not fulfilled.
     *
     * @return array of message for each validation;
     * */
    protected function stepValidationMessages(): array
    {
        return [];
    }
    /**
     * Method that retrive the array of
     * rules from the output of the method stepRules().
     * It will check if the array has been created or not,
     * then it will get all the values from the arrays' keys.
     * Check if the value has the property form or not, and it will
     * add it if it exists and validate it.
    */
    public function nextStep(): void
    {
        $rules = $this->stepRules()[$this->step] ?? null;
        $messages = $this->stepValidationMessages()[$this->step] ?? [];

        if (!empty($rules)) {
            $resolvedRules = value($rules);

            if (!empty($resolvedRules)) {
                if (property_exists($this, 'form')) {
                    $this->form->validate($resolvedRules, $messages);
                } else {
                    $this->validate($resolvedRules, $messages);
                }
            }
        }

        $this->step++;
    }

    public function previousStep(): void
    {
        if($this->step > 1) {
            $this->step--;
        }
    }
}
