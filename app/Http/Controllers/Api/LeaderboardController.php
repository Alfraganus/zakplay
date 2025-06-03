<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\LeaderboardResult;
use App\Modules\test\models\UserTestResult;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Leaderboard",
 *     description="API Endpoints for managing leaderboards"
 * )
 */
class LeaderboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/leaderboards",
     *     tags={"Leaderboard"},
     *     summary="Get all leaderboards",
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function index()
    {
        $leaderboards = Leaderboard::withoutGlobalScope('active')->with('test')->withCount('results')->get();

        return response()->json($leaderboards);
    }

    /**
     * @OA\Post(
     *     path="/api/leaderboards",
     *     tags={"Leaderboard"},
     *     summary="Create a new leaderboard",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "start_date","finish_date"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="finish_date", type="string", format="date"),
     *             @OA\Property(property="test_type", type="string"),
     *             @OA\Property(property="test_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'finish_date' => 'required|date',
            'name' =>        'required|string',
            'test_type' => 'required|string',
            'test_id' => 'exists:roadmap_test,id',
        ]);

        $leaderboard = Leaderboard::create($validated);

        return response()->json($leaderboard, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/leaderboards/{id}",
     *     tags={"Leaderboard"},
     *     summary="Get a single leaderboard",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show($id)
    {
        $leaderboard = Leaderboard::withoutGlobalScope('active')->with('test')->find($id);

        if (!$leaderboard) {
            return response()->json(['message' => 'Leaderboard not found'], 404);
        }

        return response()->json($leaderboard);
    }

    /**
     * @OA\Put(
     *     path="/api/leaderboards/{id}",
     *     tags={"Leaderboard"},
     *     summary="Update a leaderboard",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", format="string"),
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="finish_date", type="string", format="date"),
     *             @OA\Property(property="test_type", type="string"),
     *             @OA\Property(property="test_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function update(Request $request, $id)
    {
        $leaderboard = Leaderboard::withoutGlobalScope('active')->find($id);

        if (!$leaderboard) {
            return response()->json(['message' => 'Leaderboard not found'], 404);
        }

        $validated = $request->validate([
            'start_date'   => 'sometimes|date',
            'name'         => 'sometimes|string',
            'finish_date'  => 'sometimes|date',
            'is_active'    => 'sometimes|boolean',
            'test_type'    => 'sometimes|string',
            'test_id'      => 'sometimes|exists:roadmap_test,id',
        ]);

        $leaderboard->update($validated);

        return response()->json($leaderboard);
    }

    /**
     * @OA\Delete(
     *     path="/api/leaderboards/{id}",
     *     tags={"Leaderboard"},
     *     summary="Delete a leaderboard",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function destroy($id)
    {
        $leaderboard = Leaderboard::find($id);

        if (!$leaderboard) {
            return response()->json(['message' => 'Leaderboard not found'], 404);
        }

        $leaderboard->delete();

        return response()->json(null, 204);
    }


    /**
     * @OA\Get(
     *     path="/api/leaderboards/{id}/results",
     *     tags={"Leaderboard"},
     *     summary="Get all test results by leaderboard ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Leaderboard not found")
     * )
     */
    public function getResults($id)
    {
        $leaderboard = Leaderboard::with('test.department')->find($id);

        if (!$leaderboard) {
            return response()->json(['message' => 'Leaderboard not found'], 404);
        }

        $results = LeaderboardResult::where('leaderboard_id', $id)
            ->with([
                'testResult.user',
                'testResult.test',
            ])
            ->get();

        $numberOfUsers = $results->count();


        return response()->json([
            'leaderboard_id' => $leaderboard,
            'number_of_users' => $numberOfUsers,
            'results' => $results,
        ]);
    }

}
