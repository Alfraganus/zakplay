<?php
namespace App\Modules\test\swagger\tablet;


/**
 * @OA\Put(
 *     path="/api/tablets/{id}",
 *     summary="Update tablet",
 *     tags={"Tablets"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated Tablet"),
 *             @OA\Property(property="date", type="string", format="date", example="2025-06-01"),
 *             @OA\Property(property="is_active", type="boolean", example=false),
 *             @OA\Property(property="current_address", type="string", example="Samarkand"),
 *             @OA\Property(property="driver_id", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tablet updated"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tablet not found"
 *     )
 * )
 */
class  UpdateTablet{}
