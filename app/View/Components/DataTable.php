<?php

namespace App\View\Components;

use DateTime;
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
        $key = $column['key'];

        if (str_contains($key, '.')) {
            return data_get($row, $key);
        }

        return $row->$key ?? null;
    }

    /**
     * Format the value from a column function. If it has it.
    **/
    public function formatValue($value, array $column): string
    {
        if ($value === null) {
            return '—';
        }

        if (isset($column['format']) && is_callable($column['format'])) {
            return $column['format']($value);
        }


        $type = $column['type'] ?? 'text';

        return match($type) {
            'date' => $this->formatDate($value),
            'number' => number_format($value, 0),
            default => (string) $value,
        };
    }

    /**
     * Check if a @param $value it's a DateTime type and convert it into day-month-Year format
     **/
    private function formatDate($value)
    {
        if ($value instanceof DateTime) {
            return $value->format('d-m-Y');
        }

        return date('d-m-Y', strtotime($value));
    }

    /**
     * Set column visibility
     **/
    public function isColumnVisible(array $column): bool
    {
        return $column['visible'] ?? true;
    }

    /**
     * Hide column on mobile format
     **/
    public function isHiddenOnMobile(array $column): bool
    {
        return $column['hiddenOnMobile'] ?? false;
    }

    /**
     * Count how many visibile columns are available.
     **/
    public function getVisibleColumnsCount(): int
    {
        return collect($this->columns)
                ->filter(fn($col) => $this->isColumnVisible($col))
                ->count() + (count($this->actions) > 0 ? 1 : 0);
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.data-table');
    }
}
