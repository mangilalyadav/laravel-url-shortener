<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;


class Permission extends SpatiePermission
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable(['name', 'display_name', 'group_id']);
    }

    /* Table Relationships - START */
    public function group()
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id');
    }
    /* Table Relationships - END */
}
