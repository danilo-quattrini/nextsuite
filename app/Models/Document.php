<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'document_path',
        'type',
    ];
    /**
     * Get the request made to generate this document
     **/
    public function documentRequest(): HasOne
    {
        return $this->hasOne(DocumentRequest::class);
    }

    /**
     * Get all the customer that are assigned to this document.
    **/
    public function customers(): MorphToMany
    {
        return $this->morphToMany(Customer::class, 'documentable');
    }
}
