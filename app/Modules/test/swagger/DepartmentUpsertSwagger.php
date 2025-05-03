<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Post(
 *      path="/api/zakplay/department/upsert",
 *      operationId="upsertDepartment",
 *     tags={"department"},
 *      summary="Create a new department",
 *      description="Endpoint to create a new test",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="department_name_", type="string", example="Karzinka")
 *             )
 *         )
 *     ),
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


class DepartmentUpsertSwagger {}
