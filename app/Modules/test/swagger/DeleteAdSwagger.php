<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Delete(
 *     path="/api/ads/{id}",
 *     summary="Delete an ad by ID",
 *     tags={"Ads"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the ad to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Ad deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Ad deleted successfully")
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
class DeleteAdSwagger
{
}
