<?php

namespace App\Traits;

use App\Models\Customer;
use App\Models\Template;
use Livewire\Attributes\On;
use Spatie\Activitylog\Models\Activity;

trait DeleteModal
{
    public bool $showDeleteModal = false;
    public string $modelType;
    public int $id;

    #[On('delete-element')]
    public function openDeleteModal(int $id, string $type): void
    {
        $model = match ($type) {
            'customer' => Customer::findOrFail($id),
            'template' => Template::findOrFail($id),
            default => throw new \InvalidArgumentException('Invalid reviewable type'),
        };

        $this->confirmDelete($model);
    }

    public function confirmDelete($model): void
    {
        $this->id = $model->id;
        $this->modelType = get_class($model);
        $this->showDeleteModal = true;
    }

    public function deleteModelElement(): void
    {
        $model = ($this->modelType)::findOrFail($this->id);
        $modelName = class_basename($this->modelType);

        $model->delete();

        Activity::all()->last();

        $this->reset([
            'showDeleteModal',
            'id',
            'modelType'
        ]);

         session()->flash('info', $modelName . ' delete successfully.');


        $this->redirect(route('customer.list'), navigate: true);
    }


}