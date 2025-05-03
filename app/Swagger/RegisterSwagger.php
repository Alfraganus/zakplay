<?php
namespace App\Swagger;

/**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register a new user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","password_confirmation"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="1234"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="1234")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Successful registration"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */

class  RegisterSwagger{}
