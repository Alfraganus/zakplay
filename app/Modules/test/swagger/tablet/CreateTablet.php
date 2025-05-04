<?php
namespace App\Modules\test\swagger\tablet;

/**
 * @OA\Post(
 *     path="/api/tablets",
 *     summary="Create new tablet",
 *     tags={"Tablets"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","date","current_address","driver_id"},
 *             @OA\Property(property="name", type="string", example="Tablet 1"),
 *             @OA\Property(property="date", type="string", format="date", example="2025-05-04"),
 *             @OA\Property(property="is_active", type="boolean", example=1),
 *             @OA\Property(property="current_address", type="string", example="Tashkent, Uzbekistan"),
 *             @OA\Property(property="driver_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Tablet created"
 *     )
 * )
 */

class  CreateTablet{}
