<?php
namespace App\Swagger;

/**
 * @OA\Get(
 *     path="/api/weather/current",
 *     tags={"Weather"},
 *     summary="Get current weather by city",
 *     description="Returns the current weather data for a given city. Defaults to Tashkent.",
 *     @OA\Parameter(
 *         name="city",
 *         in="query",
 *         required=false,
 *         description="City name to fetch weather for",
 *         @OA\Schema(type="string", example="Tashkent")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="location", type="object"),
 *             @OA\Property(property="current", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request"
 *     )
 * )
 */


class  WeatherCurrentSwagger{}
