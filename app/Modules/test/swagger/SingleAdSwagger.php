<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/ads/{id}",
 *     summary="Get a single ad by ID",
 *     tags={"Ads"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the ad",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Ad found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Ad not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Ad not found")
 *         )
 *     )
 * )
 */
class SingleAdSwagger
{
}
