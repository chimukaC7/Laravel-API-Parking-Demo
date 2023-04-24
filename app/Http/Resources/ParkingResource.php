<?php

namespace App\Http\Resources;

use App\Services\ParkingPriceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //if the user wants to find the current price before the parking is stopped?
        // Well, we can call the calculation directly on the API Resource file:
        $totalPrice = $this->total_price ?? ParkingPriceService::calculatePrice($this->zone_id, $this->start_time, $this->stop_time);

        return [
            'id' => $this->id,
            'zone' => [
                'name' => $this->zone->name,
                'price_per_hour' => $this->zone->price_per_hour,
            ],
            'vehicle' => [
                'plate_number' => $this->vehicle->plate_number,
                'description' => $this->vehicle->description,
            ],
            //Also, the stop_time field has a question mark, because it may be null,
            // so we use the syntax stop_time?->method() to avoid errors about using a method on a null object value.
            'start_time' => $this->start_time->toDateTimeString(),
            'stop_time' => $this->stop_time?->toDateTimeString(),
            'total_price' => $totalPrice,
        ];
    }
}
