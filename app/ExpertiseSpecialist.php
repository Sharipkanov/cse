<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertiseSpecialist extends Model
{
    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id', 'id')->first();
    }

    public function speciality()
    {
        return $this->belongsTo(ExpertiseSpeciality::class, 'expertise_speciality_id', 'id')->first();
    }
}
