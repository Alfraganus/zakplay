<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/roadmap/test/find-by-id/{id}",
 *     summary="Get a Roadmap Test by ID",
 *     description="Retrieve details of a Roadmap Test by its ID.",
 *     operationId="findById",
 *     tags={"Roadmap test"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
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
 *         description="Test not found"
 *     )
 * )
 */

class GetTestByIdSwagger {}
