<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissionRole extends Model
{
    use HasFactory;
    protected $fillable = ['entity_id', 'action_id'];

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }
    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_roles', 'permission_id','roles_id');
    }
}



