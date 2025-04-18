<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Post(
 *     path="/api/zakplay/test/answers/show",
 *     operationId="submitshowAnswer",
 *     tags={"zakplay test"},
 *     summary="Submitting test answers",
 *     @OA\Parameter(
 *         name="device_id",
 *         in="header",
 *         required=true,
 *         @OA\Schema(type="string", example="abs"),
 *         description="Device ID from header",
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success response description",
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request response description",
 *     ),
 * )
 */
class GetTestHistoryByUserIdSwagger
{
}
