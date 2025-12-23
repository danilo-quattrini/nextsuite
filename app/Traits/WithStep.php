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
                $this->form->validate($resolvedRules);
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
