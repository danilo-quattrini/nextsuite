<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DataTable extends Component
{

    /**
     * Create a new component instance.
     * @param $tableData - data we are going to insert inside the table
     * @param  array  $columns - array of columns to display with settings (label, icon, etc.)
     * @param array $actions - array of action to perform in each row
     * @param string $resourceType - type of data it's gonna show the table, default it's an item
     * @param ?string $photoField - optional field for a photo, for instance, $model->{$photoField}
     * @param ?string $nameField - optional field for the name of the item, for instance, $model->{$nameField}
     */
    public function __construct(
        public $tableData,
        public array $columns,
        public array $actions = [],
        public string $resourceType = 'item',
        public ?string $photoField = null,
        public ?string $nameField = null

    ) {}


    /**
     * Get the value for a specific column from a row
     */
    public function getColumnValue($row, array $column)
    {
        $key = $column['key'];  // e.g., 'company.name'

        // Check if it's a relationship (has a dot)
        if (str_contains($key, '.')) {
            // Use Laravel's data_get helper
            return data_get($row, $key);
        }

        // Simple field
        return $row->$key ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.data-table');
    }
}
