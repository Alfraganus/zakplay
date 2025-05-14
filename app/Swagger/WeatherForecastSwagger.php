<?php
namespace App\Swagger;

/**
 * @OA\Get(
 *     path="/api/weather/forecast",
 *     tags={"Weather"},
 *     summary="Get weather forecast",
 *     description="Returns weather forecast data for a given city and number of days (1-10).",
 *     @OA\Parameter(
 *         name="city",
 *         in="query",
 *         required=false,
 *         description="City name to fetch forecast for",
 *         @OA\Schema(type="string", example="Tashkent")
 *     ),
 *     @OA\Parameter(
 *         name="days",
 *         in="query",
 *         required=false,
 *         description="Number of days to get forecast for (max: 10)",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Forecast returned successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="location", type="object"),
 *             @OA\Property(property="forecast", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request"
 *     )
 * )
 */



class  WeatherForecastSwagger{}
