<?php

namespace App\Modules\test\service;

use App\Models\DeviceToken;
use App\Models\Leaderboard;
use App\Models\PlatformUser;
use App\Models\Setting;
use App\Models\User;
use App\Modules\test\models\RoadmapTest;
use App\Modules\test\models\RoadmapTestQuestion;
use App\Modules\test\models\UserTestResult;
use App\Modules\test\repository\RoadmapTestAnswersRepository;
use App\Modules\test\repository\RoadmapTestQuestionOptionRepository;
use App\Modules\test\repository\RoadmapTestQuestionRepository;
use App\Modules\test\repository\RoadmapTestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoadmapTestSubmitService
{
    private $roadmapTestQuestionRepo;
    private $roadmapTestQuestionOptionRepo;
    private $roadmapTestHistory;
    private $roadmapTestAnswersRepo;
    private $roadmapLevelService;
    private $roadmapTestRepository;

    const QUESTION_TYPE_ONLY_ONE_CORRECT_OPTION = 1;
    const QUESTION_TYPE_MORE_CORRECT_OPTIONS = 2;
    const QUESTION_TYPE_MATCHING_OPTIONS = 3;
    const QUESTION_TYPE_BOOLEAN_OPTION = 4;

    public function __construct(
        RoadmapTestQuestionRepository       $roadmapTestQuestion,
        RoadmapTestQuestionOptionRepository $roadmapTestQuestionOption,
        RoadmapTestAnswersRepository        $roadmapTestAnswersRepository,
        RoadmapTestRepository               $roadmapTestRepository,
    )
    {
        $this->roadmapTestQuestionRepo = $roadmapTestQuestion;
        $this->roadmapTestQuestionOptionRepo = $roadmapTestQuestionOption;
        $this->roadmapTestAnswersRepo = $roadmapTestAnswersRepository;
        $this->roadmapTestRepository = $roadmapTestRepository;
    }

    public static function getUserData(Request $request)
    {
        if ($request->input('phone') && $request->input('fullname')) {
            $user = PlatformUser::firstOrNew(
                ['phone' => $request->input('phone')]
            );

            $user->fullname = $request->input('fullname');
            $user->save();
            return $user;
        }
        return null;
    }

    public function updateResult(Request $request, $test_result_id)
    {
        $testResult = UserTestResult::query()->find($test_result_id);
        $testResult->user_id = self::getUserData($request)->id;
        $testResult->save();
        $ranks = UserTestResult::getUsersRank($request, $testResult->test_id);

        return [
          'msg' =>'Test has been updated',
          'ranks' => $ranks
        ];
    }

    public function submitAnswers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_id' => 'required|integer|exists:roadmap_test,id',
            'fullname' => 'string|max:255',
            'phone' => 'numeric|digits_between:9,15',
            'test_answers' => 'required|array|min:1',
            'test_answers.*.question_id' => 'required|integer|exists:roadmap_test_questions,id',
            'test_answers.*.options' => 'required|array|min:1',
            'test_answers.*.options.*' => 'required|integer|exists:roadmap_test_question_options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $correctAnswers = 0;
        if (!$this->roadmapTestRepository->getById($request->input('test_id'))) {
            return Response()->json([
                'error' => 'Test not found'
            ], 404);
        }
        foreach ($request->input('test_answers') as $answer) {
            $correctOption = 0;
            $incorrectOption = 0;
            $question = $this->roadmapTestQuestionRepo->getById($answer['question_id']);
            $options = $this->roadmapTestQuestionOptionRepo
                ->getByQuestionId(
                    $answer['question_id']
                )
                ->where('is_correct', true);
            $correctOptions = $options->pluck('id')->toArray();
            $correctanswer = $options->first();
            switch ($question->question_option_type) {

                case self::QUESTION_TYPE_ONLY_ONE_CORRECT_OPTION || self::QUESTION_TYPE_BOOLEAN_OPTION:
                    $this->sumSingleCorrectOption($correctanswer, $answer, $correctAnswers);
                    break;

                case self::QUESTION_TYPE_MORE_CORRECT_OPTIONS:
                    $this->sumMultipleCorrectOptions(
                        $answer,
                        $correctOptions,
                        $correctOption,
                        $incorrectOption,
                        $correctAnswers
                    );
                    break;
            }
        }
        $test_id = $request->input('test_id');
        $testQuestionsCount = RoadmapTestQuestion::query()
            ->where('test_id', '=', $request->input('test_id'))
            ->count();
        $isUserPassed = $testQuestionsCount == $correctAnswers;

        $leaderboard = Leaderboard::where('finish_date', '>=', date('Y-m-d'))
            ->where(function ($query) use ($test_id) {
                $query->where('test_id', $test_id)
                    ->orWhere('test_type', Leaderboard::ALL_TEST);
            })
            ->first();

        $leaderboard_id = $leaderboard ? $leaderboard->id : null;
        $testResult = UserTestResult::query()->create([
            'user_id' => self::getUserData($request)->id ?? null,
            'test_id' => $test_id,
            'test_result' => $correctAnswers,
            'percentage' => ($correctAnswers / $testQuestionsCount) * 100,
            'max_score' => $testQuestionsCount,
            'device_id' => $request->header('device_id') ?? null,
            'is_passed' => $isUserPassed,
            'average_time' => $request->input('average_time'),
            'leaderboard_id' => $leaderboard_id,
        ]);

        $roadmapTest = RoadmapTest::find($test_id);
        if ($roadmapTest) {
            $roadmapTest->increment('used_times');
            $roadmapTest->save();
        }

        return Response()->json([
            'msg' => 'Answers have been recorded successfully!',
            'rest_result_id' => $testResult->id,
            'result' => UserTestResult::checkIfUserTopRank($test_id, $testResult->id),
            'user_rank' => $isUserPassed,
            'correctAnswers' => $correctAnswers,
        ]);
    }

    public function sumMultipleCorrectOptions($answer, $correctOptions, &$correctOption, &$incorrectOption, &$correctAnswers)
    {
        foreach ($answer['options'] as $option) {
            if (in_array($option, $correctOptions)) {
                $correctOption++;
            } else {
                $incorrectOption++;
            }
        }

        if (count($correctOptions) == $correctOption && $incorrectOption == 0) {
            $correctAnswers++;
        }
    }

    public function sumMatcingTypeOptions($answer, $question, &$correctAnswers): void
    {
        if ($question->getTranslations('question_text_')[Setting::getLanguage()] == $answer['match_type_options']) {
            $correctAnswers++;
        }
    }

    public function sumSingleCorrectOption($correctOption, $answer, &$correctAnswers)
    {
        if ($correctOption && $correctOption->id == $answer['options'][0]) {
            $correctAnswers++;
        }
    }

    public function getUserTestResult($deviceId)
    {
        return [
            'device' => DeviceToken::query()->where('device_token', $deviceId)->with('user')->first(),
            'data' => $this->roadmapTestHistory->findAllTestHistory($deviceId)
        ];
    }

}
