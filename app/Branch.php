<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id', 'id')->first();
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'branch_id', 'id')->where('parent_id', 0)->get();
    }
}
