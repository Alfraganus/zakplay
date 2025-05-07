<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Car Models",
 *     description="API Endpoints for Car Models"
 * )
 */
class CarsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/car-models",
     *     summary="Get all car models",
     *     tags={"Car Models"},
     *     @OA\Response(response=200, description="List of car models")
     * )
     */
    public function index()
    {
        return response()->json(CarModel::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/car-models",
     *     summary="Create a new car model",
     *     tags={"Car Models"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="brand", type="string", example="Chevrolet"),
     *             @OA\Property(property="name", type="string", example="Cobalt")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Car model created")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $carModel = CarModel::create($validated);

        return response()->json($carModel, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/car-models/{id}",
     *     summary="Get a specific car model",
     *     tags={"Car Models"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Car model found"),
     *     @OA\Response(response=404, description="Car model not found")
     * )
     */
    public function show($id)
    {
        $carModel = CarModel::find($id);

        if (!$carModel) {
            return response()->json(['error' => 'Car model not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($carModel, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/car-models/{id}",
     *     summary="Update a car model",
     *     tags={"Car Models"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="brand", type="string", example="Kia"),
     *             @OA\Property(property="name", type="string", example="K5")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Car model updated"),
     *     @OA\Response(response=404, description="Car model not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $carModel = CarModel::find($id);

        if (!$carModel) {
            return response()->json(['error' => 'Car model not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'brand' => 'nullable|string|max:255',
            'name' => 'sometimes|required|string|max:255',
        ]);

        $carModel->update($validated);

        return response()->json($carModel, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/car-models/{id}",
     *     summary="Delete a car model",
     *     tags={"Car Models"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Car model deleted"),
     *     @OA\Response(response=404, description="Car model not found")
     * )
     */
    public function destroy($id)
    {
        $carModel = CarModel::find($id);

        if (!$carModel) {
            return response()->json(['error' => 'Car model not found'], Response::HTTP_NOT_FOUND);
        }

        $carModel->delete();

        return response()->json(['message' => 'Car model deleted'], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/car-colors",
     *     summary="Get all car colors in Uzbek",
     *     tags={"Car Models"},
     *     @OA\Response(response=200, description="List of car colors")
     * )
     */
    public function getCarColors()
    {
        return response()->json(CarModel::$carColors, Response::HTTP_OK);
    }
}
