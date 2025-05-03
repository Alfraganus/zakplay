<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/zakplay/department/all",
 *     summary="Get deoartnebts",
 *     description="Retrieve departments",
 *     operationId="getdepartment",
 *     tags={"department"},
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

class GetDepartmentsSwagger {}
