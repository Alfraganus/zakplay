<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Post(
 *     path="/api/zakplay/test/answers/update/{test_result_id}",
 *     tags={"ZakPlay - Test"},
 *     summary="Update test answers",
 *     description="Submit or update answers for a test by providing user's full name and phone number.",
 *     operationId="updateTestAnswers",
 *     @OA\Parameter(
 *         name="test_result_id",
 *         in="path",
 *         required=true,
 *         description="ID of the test result",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"fullname", "phone"},
 *             @OA\Property(property="fullname", type="string", example="John Doe"),
 *             @OA\Property(property="phone", type="string", example="+998901234567")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful update",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Test answers updated successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid data.")
 *         )
 *     )
 * )
 */

class TestAnswerUpdateSwagger
{
}
