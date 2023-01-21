<?php

namespace App\Observers;

use App\Models\Parking;

class ParkingObserver
{
    public function creating(Parking $parking)
    {
        //we will also use the user_id multi-tenancy here, like in the Vehicles?
        //Not only that, but in this case, we also auto-set the start_time value.
        if (auth()->check()) {
            $parking->user_id = auth()->id();
        }

        $parking->start_time = now();
    }
}
