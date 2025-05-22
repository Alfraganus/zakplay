<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Post(
 *     path="/api/ads/upsert",
 *     summary="Create or update an ad",
 *     tags={"Ads"},
 *     operationId="upsertAds",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"department_id", "title", "ad_uz", "ad_ru"},
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     description="Ad ID (for update only)"
 *                 ),
 *                 @OA\Property(
 *                     property="department_id",
 *                     type="integer",
 *                     description="ID of the department"
 *                 ),
 *                 @OA\Property(
 *                     property="ad_type",
 *                     type="string",
 *                     description="type of the department"
 *                 ),
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     description="Title of the ad"
 *                 ),
 *                 @OA\Property(
 *                     property="is_active",
 *                     type="integer",
 *                     example=1,
 *                     description="Whether the ad is active"
 *                 ),
 *                 @OA\Property(
 *                     property="ad_uz",
 *                     type="file",
 *                     description="Media file for English version"
 *                 ),
 *                 @OA\Property(
 *                     property="ad_ru",
 *                     type="file",
 *                     description="Media file for Russian version"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="data", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class UpsertAdSwagger
{
}
