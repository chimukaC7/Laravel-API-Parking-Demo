<?php

namespace App\Observers;

use App\Models\Vehicle;

class VehicleObserver
{
    //Then we fill in the creating() method. Important notice: it's creating(), not created().
    public function creating(Vehicle $vehicle)
    {
        if (auth()->check()) {
            //we still need to pass the same Auth Bearer token
            //That will determine the auth()->id() value for the Observer
            $vehicle->user_id = auth()->id();
        }
    }
}
