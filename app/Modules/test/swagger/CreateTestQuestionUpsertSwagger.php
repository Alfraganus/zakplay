<?php
namespace App\Modules\test\swagger;

/**
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *    description="Enter 'Bearer ' (with a trailing space) followed by your token in the Authorization header. Example: 'Bearer YOUR_TOKEN'"
 * )
 *
 * @OA\Info(
 *     version="1.0",
 *     title="Zakplay API endpoints"
 * )
 * @OA\Post(
 *      path="/api/zakplay/test-question/upsert",
 *      operationId="createTestQuestionupsert",
 *      tags={"zakplay test"},
 *      summary="Create a new test",
 *      description="Endpoint to create a new test",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="questionInfo", type="object",
 *                  @OA\Property(property="test_id", type="string", example="2"),
 *                  @OA\Property(property="question_text", type="string", example="What is the capital of France?"),
 *                  @OA\Property(property="points", type="integer", example=10),
 *                   @OA\Property(property="language", type="string", example="uz_cyril"),
 *              ),
 *              @OA\Property(property="optionsInfo", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="option_text", type="string", example="Paris"),
 *                      @OA\Property(property="is_correct", type="boolean", example=true)
 *                  ),
 *                  @OA\Items(
 *                      @OA\Property(property="option_text", type="string", example="Berlin"),
 *                      @OA\Property(property="is_correct", type="boolean", example=false)
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
