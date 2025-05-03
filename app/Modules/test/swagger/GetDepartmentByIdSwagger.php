<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/zakplay/department/find-by-id/{id}",
 *     summary="Get a department by ID",
 *     description="Retrieve details of a zakplay department by its ID.",
 *     operationId="findByIdde",
 *     tags={"department"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the zakplay test"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Test not found"
 *     )
 * )
 */

class GetDepartmentByIdSwagger {}
