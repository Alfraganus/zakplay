<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Post(
 *     path="/api/roadmap/test/upsert",
 *     summary="Upsert a roadmap test",
 *     description="Creates or updates a roadmap lesson",
 *     operationId="upsertRoadmapTest",
 *      tags={"Roadmap test"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="title", type="string", example="Roadmap test Title"),
 *                 @OA\Property(property="description", type="string", example="Roadmap test Description"),
 *                 @OA\Property(property="lesson_id", type="integer", example=1),
 *                 @OA\Property(property="language", type="string", example="uz_cyril"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="string"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="string"
 *             )
 *         )
 *     )
 * )
 */

class UpsertRoadmapTestSwagger
{
}
