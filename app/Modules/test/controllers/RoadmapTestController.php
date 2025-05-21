<?php
namespace App\Modules\test\controllers;

use App\Helpers\MultiLanguageModelService;
use App\Http\Controllers\Controller;
use App\Modules\Roadmap\roadmapLesson\models\RoadmapLesson;
use App\Modules\test\models\Department;
use App\Modules\test\models\RoadmapTest;
use App\Modules\test\repository\RoadmapTestRepository;
use App\Modules\test\service\RoadmapTestCreateService;
use App\Modules\test\service\RoadmapTestSubmitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoadmapTestController extends Controller
{
    protected $testService;
    protected $testSubmitService;
    protected $roadmapTestRepo;

    public function __construct(
        RoadmapTestCreateService $testService,
        RoadmapTestRepository $roadmapTestRepository,
        RoadmapTestSubmitService $testSubmitService
    )
    {
        $this->testService = $testService;
        $this->roadmapTestRepo = $roadmapTestRepository;
        $this->testSubmitService = $testSubmitService;
    }

    public function upsertRoadmapTest(Request $request)
    {
        return MultiLanguageModelService::roadmapGlobalInsert(
            $request,
            RoadmapTest::class,
            [],
        );
    }


    public function getAllTestByDepartmentId(Request $request)
    {
        $tests = RoadmapTest::where('department_id', $request->input('departmentId'))->get();

        if ($tests->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tests found for the given department'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tests
        ]);
    }



    public function update(Request $request, $id)
    {
        $model = RoadmapTest::find($id);

        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'Roadmap test not found'
            ], 404);
        }

        $model->fill($request->only([
            'ad_place',
            'ad_after_question',
            'ad_id',
            'views_limit',
            'time_for_question',
        ]));

        $model->save();

        return response()->json([
            'success' => true,
            'message' => 'Roadmap test updated successfully',
            'data' => $model
        ]);
    }


    public function findById($id)
    {
        $model =  $this->roadmapTestRepo->getById($id);
        if(empty($model)) {
           return Response()->json([
               'error'=>"Result not found"
           ]);
        }
        return Response()->json([
            'data'=>$model
        ]);
    }

    public function deleteTest(Request $request)
    {
        return $this->testService->deleteTest($request->input('id'));
    }

    public function submitAnswers(Request $request)
    {
      return $this->testSubmitService->submitAnswers($request);
    }

    public function updateTestResult(Request $request, $testId)
    {
        return $this->testSubmitService->updateResult($request,$testId);

    }

    public function getAnswers(Request $request)
    {
        return $this->testSubmitService->getUserTestResult($request->header('device_id'));
    }

}
