<?php

namespace App\Modules\test\service;

use App\Helpers\MultiLanguageModelService;
use App\Modules\Dhikr\Models\DhikrCategory;
use App\Modules\Qurans\models\QuranFacts;
use App\Modules\test\models\RoadmapTest;
use App\Modules\test\repository\RoadmapTestAnswersRepository;
use App\Modules\test\repository\RoadmapTestRepository;
use App\Modules\test\validation\TestCreateValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoadmapTestCreateService
{
    protected $repository;
    protected $questionService;
    protected $testQuestionService;
    protected $roadmapTestAnswersRepository;

    public function __construct(
        RoadmapTestQuestionService   $questionService,
        RoadmapTestRepository        $repository,
        RoadmapTestQuestionService   $roadmapTestQuestionService,
        RoadmapTestAnswersRepository $roadmapTestAnswersRepository
    )
    {
        $this->questionService = $questionService;
        $this->repository = $repository;
        $this->testQuestionService = $roadmapTestQuestionService;
        $this->roadmapTestAnswersRepository = $roadmapTestAnswersRepository;
    }

    public function createRoadmapTest(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), TestCreateValidation::Testrules());
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $requestData = $request->input('testInfo');

            $result = $this->repository->create($requestData);
            DB::commit();
            return response()->json([
                'message' => 'Test created successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create test. ' . $e->getMessage()], 500);
        }
    }

    public function updateTest(Request $request, $id)
    {
        return $this->repository->update($request->all(),$id);
    }

    public function deleteTest($id)
    {
        return $this->repository->delete($id);
    }

    public function getLessonTest($lesson_id)
    {
        $getByLessonId = $this->repository->getByKey('lesson_id',$lesson_id)->first();
        if($getByLessonId) return $getByLessonId->id;
        $model = new RoadmapTest();
        $model->setTranslation('title_','en',"test for lesson_id $lesson_id");
        $model->setTranslation('description_','en',"description for lesson_id $lesson_id");
        $model->lesson_id = $lesson_id;
        $model->purpose_id = 2;
        $model->save();

        return  $model->id;
    }
}
