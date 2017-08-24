<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }

    public function category()
    {
        return $this->belongsTo(ExpertiseCategory::class, 'category_id', 'id')->first();
    }

    public function status()
    {
        return $this->belongsTo(ExpertiseStatus::class, 'expertise_status', 'id')->first();
    }

    public function addition_status()
    {
        return $this->belongsTo(ExpertiseStatus::class, 'expertise_additional_status', 'id')->first();
    }

    public function region()
    {
        return $this->belongsTo(ExpertiseRegion::class,'expertise_region_id', 'id')->first();
    }

    public function agency()
    {
        return $this->belongsTo(ExpertiseAgency::class, 'expertise_agency_id', 'id')->first();
    }

    public function organ()
    {
        return $this->belongsTo(ExpertiseOrgan::class, 'expertise_organ_id', 'id')->first();
    }

    public function infos()
    {
        return $this->hasMany(ExpertiseInfo::class, 'expertise_id', 'id');
    }
}
