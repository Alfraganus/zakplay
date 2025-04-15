<?php

namespace App\Modules\test\service;

use App\Helpers\MultiLanguageModelService;
use App\Modules\Qurans\models\QuranFacts;
use App\Modules\Roadmap\roadmapLesson\models\RoadmapLesson;
use App\Modules\test\models\RoadmapTest;
use App\Modules\test\models\RoadmapTestQuestion;
use App\Modules\test\repository\RoadmapTestQuestionOptionRepository;
use App\Modules\test\repository\RoadmapTestQuestionRepository;
use App\Modules\test\validation\TestCreateValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoadmapTestQuestionService
{
    private $repository;
    private $optionService;
    private $questionOptionRepository;

    public function __construct(
        RoadmapTestQuestionRepository       $repository,
        RoadmapTestQuestionOptionService    $optionService,
        RoadmapTestQuestionOptionRepository $questionOptionRepository,
    )
    {
        $this->repository = $repository;
        $this->questionOptionRepository = $questionOptionRepository;
        $this->optionService = $optionService;
    }

    public function createWithLanguage($data)
    {
        $model = isset($data['id']) ? RoadmapTestQuestion::find($data['id']) : new RoadmapTestQuestion();
        $model->setTranslation('question_text_', $data['language'], $data['question_text']);
        $model->fill($data)->save();

        return $model;
    }

    public function createMatchingTypeQuestion(Request $request)
    {
        $model = $request->input('id') ? RoadmapTestQuestion::find($request->input('id')) : new RoadmapTestQuestion();
        $model->setTranslation('question_text_', $request->input('language'), $request->input('matching'));
        $model->fill($request->all())->save();
        return $model;
    }


    public function createQuestion($data)
    {
        return $this->repository->create($data);
    }

    public function updateQuestion($data)
    {
        return $this->repository->update($data);
    }

    public function upsertMatchingTypeQuestions(Request $request)
    {
        return $this->createMatchingTypeQuestion($request);
    }

    public function upsertRoadmapTestQuestionAndOptions(Request $request)
    {
        DB::beginTransaction();
        try {
            $questionData = $request->input('questionInfo');
            $questionModel = $this->createWithLanguage($questionData);

            if ($request->file('image')) {
                if ($questionModel->hasMedia(RoadmapTest::MEDIA_COLLECTION)) {
                    $questionModel->clearMediaCollection(RoadmapTest::MEDIA_COLLECTION);
                }
                $questionModel->addMedia($request->file('image'))
                    ->toMediaCollection(RoadmapTest::MEDIA_COLLECTION);
            }

            $options = [];
            foreach ($request->input('optionsInfo') as $option) {
                $option['question_id'] = $questionModel->id;
                $option['language'] = $questionData['language'] ?? null;
                $options[] = $this->optionService->createWithLanguage($option);
            }
            DB::commit();
            return response()->json([
                'message' => 'Test and its questions have been created successfully',
                'data' => [
                    'question' => $questionModel->load('media'),
                    'options' => $options,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create test. ' . $e->getMessage()], 500);
        }
    }

    public function deleteTestQuestion($question_id)
    {
        $this->questionOptionRepository->delete($question_id);
        $this->repository->deleteTestQuestion($question_id);

        return Response()->json([
            'msg' => "Test question has been deleted with its options"
        ]);
    }

    public function getAllQuestionsByTestId(Request $request)
    {
        return $this->repository->getByTestId($request->input('test_id'))->get();
    }

    public function getTestQuestionByTestId(Request $request)
    {
        $testId = $request->input('test_id');
        $findByTestId = $this->repository->getByTestId($testId);
        if (!$request->input('question_id')) {
            $question = $findByTestId->first();
        } else {
            $question = $findByTestId
                ->where('id', $request->input('question_id'))
                ->first();
        }

        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        $nextQuestion = $this->repository->getByTestId($testId)
            ->where('id', '>', $question->id)
            ->orderBy('id')
            ->first();

        $prevQuestion = $this->repository->getByTestId($testId)
            ->where('id', '<', $question->id)
            ->orderByDesc('id')
            ->first();

        $response = [
            'data' => [
                'question_info' => $question,
                'next_question_id' => $nextQuestion ? $nextQuestion->id : null,
                'prev_question_id' => $prevQuestion ? $prevQuestion->id : null,
            ]
        ];

        return response()->json($response);
    }

}
