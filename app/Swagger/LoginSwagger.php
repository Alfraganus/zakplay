<?php
namespace App\Swagger;

/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="Log in a user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="1234")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Successful login"),
 *     @OA\Response(response=422, description="Invalid credentials")
 * )
 */

class  LoginSwagger{}
