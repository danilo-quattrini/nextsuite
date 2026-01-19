<?php

namespace App\Traits;

trait WithStep
{

    public int $step = 1;
    abstract protected function stepRules(): array;

    public function nextStep(): void
    {
        $rules = $this->stepRules()[$this->step] ?? null;

        if ($rules) {
            $resolvedRules = value($rules);

            if (!empty($resolvedRules)) {
                if (property_exists($this, 'form')) {
                    $this->form->validate($resolvedRules);
                } else {
                    $this->validate($resolvedRules);
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
