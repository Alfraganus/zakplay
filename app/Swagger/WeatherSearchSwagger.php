<?php
namespace App\Swagger;

/**
 * @OA\Get(
 *     path="/api/weather/search",
 *     tags={"Weather"},
 *     summary="Search for cities",
 *     description="Returns a list of cities matching the search query.",
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         description="Partial or full city name",
 *         @OA\Schema(type="string", example="Tash")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of matching cities",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="country", type="string"),
 *                 @OA\Property(property="region", type="string"),
 *                 @OA\Property(property="lat", type="number", format="float"),
 *                 @OA\Property(property="lon", type="number", format="float")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request"
 *     )
 * )
 */

class  WeatherSearchSwagger{}
