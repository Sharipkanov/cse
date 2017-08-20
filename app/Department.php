<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id', 'id')->first();
    }

    public function departments()
    {
        return $this->where('parent_id', 0)->get();
    }

    public function subdivisions()
    {
        return $this->hasMany(Department::class, 'parent_id', 'id')->get();
    }

    public function has_subdivision()
    {
        return (count($this->subdivisions())) ? true : false;
    }

    public function department_users()
    {
        $result =  $this->hasMany(User::class, 'department_id', 'id')
            ->where('users.subdivision_id', 0);

        if($this->leader_id) $result = $result->whereNotIn('users.id', [$this->leader()->id]);

        return $result->orderBy('last_name')->get();
    }

    public function subdivision_users()
    {
        $result =  $this->hasMany(User::class, 'subdivision_id', 'id');

        if($this->leader_id) $result = $result->whereNotIn('users.id', [$this->leader()->id]);

        return $result->orderBy('last_name')->get();
    }
}
