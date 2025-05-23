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

class DepartmentController extends Controller
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

    public function upsertDepartment(Request $request)
    {
        $model = MultiLanguageModelService::roadmapGlobalInsert(
            $request,
            Department::class,
          []
        );
        $priority = 1;
        foreach (Department::has('test')->get() as $department) {
                $department->priority_number = $priority++;
                $department->save();
        }

        return $model;
    }

    public function getAllDepartments()
    {
        return Response()->json([
            'data'=> Department::withoutGlobalScope('active')->withCount('test')->get()
        ]);
    }

    public function findById($id)
    {
        $model =  Department::query()->withoutGlobalScope('active')->find($id);
        if(empty($model)) {
           return Response()->json([
               'error'=>"Result not found"
           ]);
        }
        return Response()->json([
            'data'=>$model
        ]);
    }


    public function delete($id)
    {
        $model = Department::query()->withoutGlobalScope('active')->find($id);
        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $model->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully'
        ]);
    }


}
