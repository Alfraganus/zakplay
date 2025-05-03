<?php
namespace App\Modules\test\swagger;

/**
 * @OA\Get(
 *     path="/api/zakplay/test/get-by-department_id",
 *     summary="Get all tests by department ID",
 *     description="Retrieve all roadmap tests associated with a specific department ID.",
 *     tags={"zakplay test"},
 *     @OA\Parameter(
 *         name="departmentId",
 *         in="query",
 *         description="ID of the department to get tests for",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="A list of tests for the department",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="title", type="string", example="Roadmap Test Title"),
 *                     @OA\Property(property="ad_place", type="string", example="Place of Ad"),
 *                     @OA\Property(property="views_limit", type="integer", example=100),
 *                     @OA\Property(property="time_for_question", type="integer", example=60),
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No tests found for the given department ID",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="No tests found for the given department")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal server error")
 *         )
 *     )
 * )
 */

class GetAllTestByDepartmentSwagger {}
