<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'file_path',
        'type',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the request made to generate this document
     **/
    public function documentRequest(): HasOne
    {
        return $this->hasOne(DocumentRequest::class);
    }

    /**
     * Get the model that owns this document.
    **/
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the templates used to generate this document
     **/
    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }
}
