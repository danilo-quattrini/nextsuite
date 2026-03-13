<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role;

class Field extends Model
{
    /** @use HasFactory<\Database\Factories\FieldFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_field', 'field_id', 'company_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'field_category', 'field_id', 'category_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_field', 'field_id', 'role_id');
    }
}
