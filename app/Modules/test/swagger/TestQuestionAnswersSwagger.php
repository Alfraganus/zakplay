<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Post(
 *     path="/api/zakplay/test/answers/submit",
 *     operationId="submitAnswer",
 *     tags={"zakplay test"},
 *     summary="Submitting test answers",
 *     @OA\RequestBody(
 *         description="Data format",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="test_id", type="integer", example=2),
 *             @OA\Property(
 *                 property="test_answers",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="question_id", type="integer", example=1),
 *                     @OA\Property(
 *                         property="options",
 *                         type="array",
 *                         @OA\Items(type="integer", example="1")
 *                     ),
 *                 ),
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="question_id", type="integer", example=2),
 *                     @OA\Property(
 *                         property="options",
 *                         type="array",
 *                         @OA\Items(type="integer", example=6),
 *                         @OA\Items(type="integer", example=7)
 *                     ),
 *                 ),
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="question_id", type="integer", example=3),
 *                     @OA\Property(
 *                         property="options",
 *                         type="array",
 *                         @OA\Items(type="integer", example=8)
 *                     ),
 *                 )
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success response description",
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request response description",
 *     ),
 * )
 */
class TestQuestionAnswersSwagger {}
