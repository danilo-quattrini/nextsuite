<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateSection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'order',
        'is_required',
        'template_id',
        'data_source',
        'formatting_rules',
    ];

    /**
     * Get all the templates related to the section
     **/
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'formatting_rules' => 'array',
        ];
    }
}
