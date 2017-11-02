<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table='manager_permissions';

    public function roles()
    {
        return $this->belongsToMany(ManagerRole::class,'manager_permission_role','permission_id','role_id');
    }

}
