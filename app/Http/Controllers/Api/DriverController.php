<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Drivers",
 *     description="Driver management endpoints"
 * )
 */
class DriverController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/drivers",
     *     summary="Get all drivers",
     *     tags={"Drivers"},
     *     @OA\Response(response=200, description="List of drivers")
     * )
     */
    public function index()
    {
        return response()->json(Driver::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/drivers",
     *     summary="Create a new driver",
     *     tags={"Drivers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fullname", "date_of_brith", "car_model", "car_color", "car_plate_number"},
     *             @OA\Property(property="fullname", type="string", example="Ali Valiyev"),
     *             @OA\Property(property="date_of_brith", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="login", type="string", example="ali123"),
     *             @OA\Property(property="pincode", type="string", example="123456"),
     *             @OA\Property(property="car_model", type="integer", example=1),
     *             @OA\Property(property="car_color", type="integer", example=2),
     *             @OA\Property(property="car_plate_number", type="string", example="01A123BC")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Driver created")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'date_of_brith' => 'required|date',
            'login' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|unique:drivers,pincode|max:255',
            'car_model' => 'required|integer',
            'car_color' => 'required|integer',
            'car_plate_number' => 'required|string|max:255',
        ]);

        $driver = Driver::create($validated);
        return response()->json($driver, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/drivers/{id}",
     *     summary="Get driver by ID",
     *     tags={"Drivers"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Driver details"),
     *     @OA\Response(response=404, description="Driver not found")
     * )
     */
    public function show($id)
    {
        try {
            $driver = Driver::findOrFail($id);
            return response()->json($driver, Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Driver not found',
                'status' => 404
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/drivers/{id}",
     *     summary="Update driver",
     *     tags={"Drivers"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="fullname", type="string", example="Updated Name"),
     *             @OA\Property(property="date_of_brith", type="string", format="date", example="1995-02-02"),
     *             @OA\Property(property="login", type="string", example="new_login"),
     *             @OA\Property(property="pincode", type="string", example="654321"),
     *             @OA\Property(property="car_model", type="integer", example=2),
     *             @OA\Property(property="car_color", type="integer", example=4),
     *             @OA\Property(property="car_plate_number", type="string", example="01X456YZ")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Driver updated"),
     *     @OA\Response(response=404, description="Driver not found")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $driver = Driver::findOrFail($id);

            $validated = $request->validate([
                'fullname' => 'sometimes|required|string|max:255',
                'date_of_brith' => 'sometimes|required|date',
                'login' => 'nullable|string|max:255',
                'pincode' => 'nullable|string|unique:drivers,pincode,' . $driver->id,
                'car_model' => 'sometimes|required|integer',
                'car_color' => 'sometimes|required|integer',
                'car_plate_number' => 'sometimes|required|string|max:255',
            ]);

            $driver->update($validated);

            return response()->json($driver, Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Driver not found',
                'status' => 404
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/drivers/{id}",
     *     summary="Delete driver",
     *     tags={"Drivers"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Driver deleted"),
     *     @OA\Response(response=404, description="Driver not found")
     * )
     */
    public function destroy($id)
    {
        try {
            $driver = Driver::findOrFail($id);
            $driver->delete();

            return response()->json([
                'message' => 'Driver deleted'
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Driver not found',
                'status' => 404
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
