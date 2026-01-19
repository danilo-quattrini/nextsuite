<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentRequest extends Model
{
    protected $fillable = [
        'type', 'status',
        'document_url', 'error_message', 'completed_at',
        'document_id',
        'requested_by_id',
        'requested_by_type'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the document that's generate by the request
    **/
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the user who initialized the generation request
     **/
    public function requestBy(): MorphTo
    {
        return $this->morphTo();
    }
}
