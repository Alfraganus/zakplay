<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Delete(
 *      path="/api/zakplay/test/delete",
 *      operationId="deleteTest",
 *      tags={"zakplay test"},
 *      summary="Delete test",
 *      @OA\Parameter(
 *          name="id",
 *          in="query",
 *          description="ID of the test question to be deleted",
 *          required=true,
 *          @OA\Schema(type="integer"),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Test question deleted successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              example={"message": "Test question deleted successfully"}
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Test question not found",
 *          @OA\JsonContent(
 *              type="object",
 *              example={"error": "Test question not found"}
 *          ),
 *      ),
 * )
 */


class DeleteTestSwagger {}
