<?php
namespace App\Swagger;

/**
 * @OA\Post (
 *     path="/api/getme",
 *     summary="Get authenticated user info",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Authenticated user info",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-01T12:00:00Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-01T12:00:00Z")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 */

class  GetMeSwagger{}
