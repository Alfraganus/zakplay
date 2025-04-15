<?php
namespace App\Modules\test\controllers;

use App\Helpers\MultiLanguageModelService;
use App\Http\Controllers\Controller;
use App\Modules\Roadmap\roadmapLesson\models\RoadmapLesson;
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
            ['title', 'description'],
        );
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

    public function getAnswers(Request $request)
    {
        return $this->testSubmitService->getUserTestResult($request->header('device_id'));
    }

}
