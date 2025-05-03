<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/ads",
 *     summary="Get all ads",
 *     tags={"Ads"},
 *     @OA\Response(
 *         response=200,
 *         description="List of ads",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(type="object")
 *             )
 *         )
 *     )
 * )
 */
class AllAdSwagger
{
}
