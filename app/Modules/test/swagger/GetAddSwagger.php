<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/zakplay/test/get",
 *     summary="Get the next test and mark the next department",
 *     description="Returns a random test from the current department based on priority, and marks the next department as is_next_one.",
 *      tags={"zakplay test"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful response with test and next department info",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="selected_test", type="object",
 *                 @OA\Property(property="id", type="integer", example=5),
 *                 @OA\Property(property="department_id", type="integer", example=2),
 *                 @OA\Property(property="name", type="string", example="Sample Test")
 *             ),
 *             @OA\Property(property="next_department_id", type="integer", example=3)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No department or no tests found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="No department found")
 *         )
 *     )
 * )
 */
class GetAddSwagger
{
}
