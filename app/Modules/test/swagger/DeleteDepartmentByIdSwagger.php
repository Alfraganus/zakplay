<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Delete (
 *     path="/api/zakplay/department/delete/{id}",
 *     summary="delete a department by ID",
 *     description="deletezakplay department by its ID.",
 *     tags={"zakplay test"},
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

class DeleteDepartmentByIdSwagger {}
