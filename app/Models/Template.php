<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'category',
        'structure',
        'layout_config',
        'blade_template',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
            'structure' => 'array',
            'layout_config' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the document related to the template
     **/
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get all the sections related to the template
     **/
    public function sections(): HasMany
    {
        return $this->hasMany(TemplateSection::class)->orderBy('order');
    }
}
