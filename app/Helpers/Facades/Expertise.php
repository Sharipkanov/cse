<?php

namespace App\Helpers\Facades;

use App\ExpertiseSpeciality;
use App\ExpertiseAgency;

class Expertise {

    public static function specialities($ids)
    {
        $expertiseSpeciality = new ExpertiseSpeciality();

        $specialities = $expertiseSpeciality->whereIn('id', explode(',', $ids))->get();

        return $specialities;
    }

    public static function agencies($regionId)
    {
        $expertiseAgencies = new ExpertiseAgency();

        $agencies = $expertiseAgencies->where('expertise_region_id', $regionId)->get();

        return $agencies;
    }
}