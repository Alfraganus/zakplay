<?php
namespace App\Modules\test\swagger\tablet;

/**
 * @OA\Delete(
 *     path="/api/tablets/{id}",
 *     summary="Delete tablet",
 *     tags={"Tablets"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tablet deleted"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tablet not found"
 *     )
 * )
 */

class  DeleteTablet{}
