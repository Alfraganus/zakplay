<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/zakplay/test/find-by-id/{id}",
 *     summary="Get a zakplay test by ID",
 *     description="Retrieve details of a zakplay test by its ID.",
 *     operationId="findById",
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

class GetTestByIdSwagger {}
