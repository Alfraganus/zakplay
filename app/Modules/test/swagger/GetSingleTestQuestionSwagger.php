<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/zakplay/test-question/get-single-question",
 *     summary="Get a Single Test Question",
 *     description="Retrieve a single test question by test_id and question_id.",
 *     operationId="getSingleQuestion",
 *     tags={"zakplay test"},
 *     @OA\Parameter(
 *         name="test_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the zakplay test"
 *     ),
 *     @OA\Parameter(
 *         name="question_id",
 *         in="query",
 *         @OA\Schema(type="integer"),
 *         description="ID of the Test Question"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Question not found"
 *     )
 * )
 */

class GetSingleTestQuestionSwagger {}
