<?php

namespace App\Modules\test\swagger;

/**
 * @OA\Put(
 *     path="/api/zakplay/test/update/{id}",
 *     summary="Update a Roadmap Test",
 *     description="Update the details of a Roadmap Test, including ad placement, views, and other parameters.",
 *     tags={"zakplay test"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the Roadmap Test to update",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"ad_place", "ad_after_question", "ad_id", "views_limit", "time_for_question"},
 *                 @OA\Property(property="ad_place", type="integer", example=1, description="The ad placement position"),
 *                 @OA\Property(property="ad_after_question", type="integer", example=5, description="The ad will appear after the question with this ID"),
 *                 @OA\Property(property="ad_id", type="integer", example=2, description="The ID of the ad"),
 *                 @OA\Property(property="views_limit", type="integer", example=100, description="The view limit for the roadmap test"),
 *                 @OA\Property(property="time_for_question", type="integer", example=60, description="Time allocated for answering a question in seconds")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully updated the Roadmap Test",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Roadmap test updated successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="ad_place", type="integer", example=1),
 *                 @OA\Property(property="ad_after_question", type="integer", example=5),
 *                 @OA\Property(property="ad_id", type="integer", example=10),
 *                 @OA\Property(property="views_limit", type="integer", example=100),
 *                 @OA\Property(property="time_for_question", type="integer", example=60)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Roadmap test not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Roadmap test not found")
 *         )
 *     )
 * )
 */
class UpdateTestSwagger
{
}
