<?php

namespace App\Modules\test\service;

use App\Modules\test\repository\RoadmapTestRepository;
use App\Modules\test\validation\TestCreateValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoadmapTestQuestionUpdateService
{
    protected $repository;
    protected $questionService;
    protected $optionService;

    public function __construct(
        RoadmapTestQuestionService       $questionService,
        RoadmapTestQuestionOptionService $optionService,
        RoadmapTestRepository            $repository
    )
    {
        $this->questionService  = $questionService;
        $this->optionService    = $optionService;
        $this->repository       = $repository;
    }

    public function updateRoadmapTestQuestion(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), TestCreateValidation::rules());

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $questionData = $request->input('questionInfo');
            $question = $this->questionService->updateQuestion($questionData);
            $options = [];
            foreach ($request->input('optionsInfo') as $option) {
                $options[] =  $this->optionService->updateOption($option);
            }
            DB::commit();
            return response()->json([
                'message' => 'Test question updated successfully',
                'data' => [
                    'questionInfo' => $question,
                    'options' => $options,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create test. ' . $e->getMessage()], 500);
        }
    }

}
