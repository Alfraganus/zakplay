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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"fullname", "date_of_brith", "car_model", "car_color", "car_plate_number"},
     *                 @OA\Property(property="fullname", type="string", example="Ali Valiyev"),
     *                 @OA\Property(property="date_of_brith", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="login", type="string", example="ali123"),
     *                 @OA\Property(property="pincode", type="string", example="123456"),
     *                 @OA\Property(property="car_model", type="string", example=1),
     *                 @OA\Property(property="car_color", type="string", example=2),
     *                 @OA\Property(property="car_plate_number", type="string", example="01A123BC"),
     *                 @OA\Property(
     *                     property="image",
     *                     type="file",
     *                     description="Driver's image"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string", default="application/json")
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
            'car_model' => 'required|string',
            'car_color' => 'required|string',
            'car_plate_number' => 'required|string|max:255',
        ]);

        $model = Driver::create($validated);
        if ($request->file('image')) {
            if ($model->hasMedia(Driver::MEDIA_COLLECTION)) {
                $model->clearMediaCollection(Driver::MEDIA_COLLECTION);
            }
            $model->addMedia($request->file('image'))
                ->toMediaCollection(Driver::MEDIA_COLLECTION);
        }
        return response()->json($model, Response::HTTP_CREATED);
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
     *             @OA\Property(property="car_model", type="string", example=2),
     *             @OA\Property(property="car_color", type="string", example=4),
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
                'car_model' => 'sometimes|required',
                'car_color' => 'sometimes|required|string',
                'car_plate_number' => 'sometimes|required|string|max:255',
            ]);

            $driver->update($validated);

            if ($request->file('image')) {
                if ($driver->hasMedia(Driver::MEDIA_COLLECTION)) {
                    $driver->clearMediaCollection(Driver::MEDIA_COLLECTION);
                }
                $driver->addMedia($request->file('image'))
                    ->toMediaCollection(Driver::MEDIA_COLLECTION);
            }

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
    /**
     * @OA\Post(
     *     path="/api/drivers/by-pin",
     *     summary="Get driver by pincode",
     *     tags={"Drivers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pincode"},
     *             @OA\Property(property="pincode", type="string", example="1234")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Driver details"),
     *     @OA\Response(response=404, description="Driver not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="Expected response format",
     *         @OA\Schema(type="string", default="application/json")
     *     )
     * )
     */
    public function getByPincode(Request $request)
    {
        $driver = Driver::with('carModel')->where('pincode', $request->pincode)->first();

        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }

        return response()->json($driver);
    }


    /**
     * @OA\Get(
     *     path="/api/drivers/models",
     *     summary="List all unique car models used by drivers",
     *     tags={"Drivers"},
     *     @OA\Response(response=200, description="List of unique car models")
     * )
     */
    public function listModels()
    {
        $models = Driver::select('car_model')->distinct()->pluck('car_model');
        return response()->json($models, Response::HTTP_OK);
    }
}
