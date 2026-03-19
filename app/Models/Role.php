<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class, 'role_field', 'role_id', 'field_id');
    }
}
