<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Response;

/**
 * @group Vehicles
 */
class VehicleController extends Controller
{
    public function index()
    {
        return VehicleResource::collection(Vehicle::all());
    }

    public function store(StoreVehicleRequest $request)
    {
        //We use $request->validated() because this is returned from the Form Request class
        $vehicle = Vehicle::create($request->validated());

        return VehicleResource::make($vehicle);
    }

    public function show(Vehicle $vehicle)
    {
        return VehicleResource::make($vehicle);
    }

    public function update(StoreVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return response()->json(VehicleResource::make($vehicle), Response::HTTP_ACCEPTED);
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->hasActiveParkings()) {
            return response()->json([
                'errors' => ['general' => ['Can\'t remove vehicle with active parkings. Stop active parking.']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $vehicle->delete();

        return response()->noContent();
    }
}
