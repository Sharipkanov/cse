<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertiseApprove extends Model
{
    public function approver()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }
}
