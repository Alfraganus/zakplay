<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DriverLocationController extends Controller
{
    /**
     * Store a new GPS location.
     *
     * @OA\Post(
     *     path="/api/driver-locations",
     *     summary="Store a new driver location",
     *     tags={"Driver Locations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"driver_id", "latitude", "longitude", "recorded_at"},
     *             @OA\Property(property="driver_id", type="integer", example=1),
     *             @OA\Property(property="latitude", type="number", format="float", example=41.2995),
     *             @OA\Property(property="longitude", type="number", format="float", example=69.2401),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Location created",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
        ]);

        $location = DriverLocation::create($validated);

        return response()->json($location, Response::HTTP_CREATED);
    }

    /**
     * Get all GPS locations.
     *
     * @OA\Get(
     *     path="/api/driver-locations",
     *     summary="List all driver locations",
     *     tags={"Driver Locations"},
     *     @OA\Response(
     *         response=200,
     *         description="List of driver locations",
     *     )
     * )
     */
    public function index()
    {
        return response()->json(DriverLocation::all());
    }

    /**
     * Show a single GPS location.
     *
     * @OA\Get(
     *     path="/api/driver-locations/{id}",
     *     summary="Show driver location by ID",
     *     tags={"Driver Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the driver location",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Driver location details",
     *     )
     * )
     */
    public function show($id)
    {
        $location = DriverLocation::findOrFail($id);
        return response()->json($location);
    }

    /**
     * Update a GPS location.
     *
     * @OA\Put(
     *     path="/api/driver-locations/{id}",
     *     summary="Update driver location by ID",
     *     tags={"Driver Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the driver location",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="latitude", type="number", format="float", example=41.3011),
     *             @OA\Property(property="longitude", type="number", format="float", example=69.2411),
     *             @OA\Property(property="recorded_at", type="string", format="date-time", example="2025-05-22 11:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated driver location",
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $location = DriverLocation::findOrFail($id);

        $validated = $request->validate([
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'recorded_at' => 'sometimes|date_format:Y-m-d H:i:s',
        ]);

        $location->update($validated);

        return response()->json($location);
    }

    /**
     * Delete a GPS location.
     *
     * @OA\Delete(
     *     path="/api/driver-locations/{id}",
     *     summary="Delete driver location by ID",
     *     tags={"Driver Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the driver location",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Driver location deleted"
     *     )
     * )
     */
    public function destroy($id)
    {
        $location = DriverLocation::findOrFail($id);
        $location->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
