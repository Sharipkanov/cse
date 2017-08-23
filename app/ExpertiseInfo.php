<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertiseInfo extends Model
{
    public function expertise()
    {
        return $this->belongsTo(Expertise::class, 'expertise_id', 'id')->first();
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }

    public function task()
    {
        return $this->hasMany(ExpertiseTask::class, 'executor_id', 'user_id')
            ->where('expertise_tasks.expertise_id', $this->expertise_id)->first();
    }
}
