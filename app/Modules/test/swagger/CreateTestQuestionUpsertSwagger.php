<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Post(
 *      path="/api/roadmap/test-question/upsert",
 *      operationId="createTestQuestionupsert",
 *      tags={"Roadmap test"},
 *      summary="Create a new test",
 *      description="Endpoint to create a new test",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="questionInfo", type="object",
 *                  @OA\Property(property="test_id", type="string", example="2"),
 *                  @OA\Property(property="question_text", type="string", example="What is the capital of France?"),
 *                  @OA\Property(property="question_option_type", type="integer", example=1),
 *                  @OA\Property(property="points", type="integer", example=10),
 *                   @OA\Property(property="language", type="string", example="uz_cyril"),
 *              ),
 *              @OA\Property(property="optionsInfo", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="option_text", type="string", example="Paris"),
 *                      @OA\Property(property="is_correct", type="boolean", example=true),
 *                      @OA\Property(property="points", type="integer", example=5),
 *                  ),
 *                  @OA\Items(
 *                      @OA\Property(property="option_text", type="string", example="Berlin"),
 *                      @OA\Property(property="is_correct", type="boolean", example=false),
 *                      @OA\Property(property="points", type="integer", example=0),
 *                  ),
 *              ),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Test created successfully",
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error or invalid input",
 *      ),
 * )
 */


class CreateTestQuestionUpsertSwagger {}
