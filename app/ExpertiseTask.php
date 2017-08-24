<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertiseTask extends Model
{
    public function expertise()
    {
        return $this->belongsTo(Expertise::class, 'expertise_id', 'id')->first();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id')->first();
    }
}
