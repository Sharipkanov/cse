<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertiseTask extends Model
{
    public function expertise()
    {
        return $this->belongsTo(Expertise::class, 'expertise_id', 'id')->first();
    }
}
