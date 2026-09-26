<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;


class Role extends SpatieRole
{
     public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable(['name', 'display_name']);
    }
}
