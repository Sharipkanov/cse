<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function director()
    {
        return $this->where('is_director', 1)->first();
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id')->first();
    }

    public function subdivision()
    {
        return $this->belongsTo(Department::class, 'subdivision_id', 'id')->first();
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id')->first();
    }

    public function documents()
    {
        return $this->hasManyThrough(Document::class, DocumentPermission::class, 'user_id', 'id', 'id')->paginate(15);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'executor_id', 'id');
    }

    public function income_registered_numbers()
    {
        return $this->hasMany(RegisterNumber::class, 'user_id', 'id')->where('is_income', 1)->get();
    }

    public function outcome_registered_numbers()
    {
        return $this->hasMany(RegisterNumber::class, 'user_id', 'id')->where('is_income', 0)->get();
    }
}
