<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id')->first();
    }

    public function correspondence()
    {
        return $this->belongsTo(Correspondence::class, 'correspondence_id', 'id')->first();
    }
}
