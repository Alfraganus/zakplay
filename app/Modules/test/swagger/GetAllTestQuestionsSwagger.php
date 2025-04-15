<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/roadmap/test-question/get-all-questions",
 *     summary="Get a Single Test Question",
 *     description="Retrieve a single test question by test_id and question_id.",
 *     operationId="getSinglequestions",
 *     tags={"Roadmap test"},
 *     @OA\Parameter(
 *         name="test_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the Roadmap Test"
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

class GetAllTestQuestionsSwagger {}
