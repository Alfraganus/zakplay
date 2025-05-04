<?php
namespace App\Modules\test\swagger\tablet;

/**
 * @OA\Get(
 *     path="/api/tablets/{id}",
 *     summary="Get tablet by ID",
 *     tags={"Tablets"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tablet details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tablet not found"
 *     )
 * )
 */

class  GetSingleTablet{}
