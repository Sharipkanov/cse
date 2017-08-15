<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id', 'id')->first();
    }

    public function subdivisions()
    {
        return $this->hasMany(Department::class, 'parent_id', 'id')->get();
    }
}
