<?php

namespace App\Modules\test\service;

use App\Models\DeviceToken;
use App\Models\Setting;
use App\Models\User;
use App\Modules\Roadmap\roadmap\service\achievements\RoadmapAchievements;
use App\Modules\Roadmap\roadmap\service\AchievementService;
use App\Modules\Roadmap\roadmap\service\RoadmapCrudService;
use App\Modules\Roadmap\roadmap\service\UserRoadmapLevelService;
use App\Modules\Roadmap\roadmapLesson\models\RoadmapLesson;
use App\Modules\Roadmap\roadmapLesson\service\RoadmapLessonCrudService;
use App\Modules\test\models\RoadmapTest;
use App\Modules\test\models\RoadmapTestQuestion;
use App\Modules\test\models\RoadmapUserTestHistory;
use App\Modules\test\repository\RoadmapTestAnswersRepository;
use App\Modules\test\repository\RoadmapTestQuestionOptionRepository;
use App\Modules\test\repository\RoadmapTestQuestionRepository;
use App\Modules\test\repository\RoadmapTestRepository;
use App\Modules\test\repository\RoadmapUserTestHistoryRepository;
use App\Modules\Users\Service\UserRewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        RoadmapUserTestHistoryRepository    $roadmapTestHistory,
        RoadmapTestAnswersRepository        $roadmapTestAnswersRepository,
        UserRoadmapLevelService             $roadmapLevelService,
        RoadmapTestRepository               $roadmapTestRepository,
        RoadmapTestCreateService            $roadmapTestCreateService,
        UserRewardService                   $userRewardService,
        RoadmapAchievements                 $roadmapAchievements,
    )
    {
        $this->roadmapTestQuestionRepo = $roadmapTestQuestion;
        $this->roadmapTestQuestionOptionRepo = $roadmapTestQuestionOption;
        $this->roadmapTestHistory = $roadmapTestHistory;
        $this->roadmapTestAnswersRepo = $roadmapTestAnswersRepository;
        $this->roadmapLevelService = $roadmapLevelService;
        $this->roadmapTestRepository = $roadmapTestRepository;
        $this->roadmapTestCreateService = $roadmapTestCreateService;
        $this->userRewardService = $userRewardService;
        $this->roadmapAchievements = $roadmapAchievements;
    }


    public function submitAnswers(Request $request)
    {
        $payload = [];
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
                case self::QUESTION_TYPE_MATCHING_OPTIONS:
                    $this->sumMatcingTypeOptions(
                        $answer,
                        $question,
                        $correctAnswers
                    );
            }
        }
        $test_id = $request->input('test_id');
        $lesson_id = $request->input('lesson_id');
        $testQuestionsCount = RoadmapTestQuestion::query()
            ->where('test_id', '=', $request->input('test_id'))
            ->count();
        $isUserPassed = $testQuestionsCount == $correctAnswers;
        $userCurrentRoadmap = $this->roadmapLevelService->getUserCurrentRoadmap();

        if ($isUserPassed) {
            /*birinchi daersni tugandan kegin achieveement berilish joyi*/
            $this->roadmapAchievements->assignRoadmapFirstLevelAchievement($test_id);

            $this->levelUpUser(
                $test_id,
                $userCurrentRoadmap['roadmap_id']
            );
            /* bu updateUserStar funksiyada ->agarda 1 urinishda yechgan bolsa, star beriladi*/
            $this->updateUserStar($request);
            /*agar 1 kunda, 1 dan ortoq testni yecha olsa, unda olov beriladi*/
            $this->userRewardService->updateFireBasedOnTests();
        } else {
            /*agarda testni yecha olmasa bitta yulduzchadan ayriladi*/
            $this->userRewardService->deductHeart();
        }
        $history = $this->roadmapTestHistory->create([
            'roadmap_id' => $userCurrentRoadmap['roadmap_id'],
            'user_id' => Auth::id(),
            'lesson_id' => $lesson_id,
            'test_id' => $test_id,
            'test_result' => $correctAnswers,
            'percentage' => ($correctAnswers / $testQuestionsCount) * 100,
            'max_score' => $testQuestionsCount,
            'device_id' => $request->header('device_id') ?? null,
            'is_passed' => $isUserPassed,
            'average_time' =>$request->input('average_time'),
        ]);

        $this->roadmapTestHistory->findById($history->id);
        $this->roadmapTestAnswersRepo->create($payload);

        return Response()->json([
            'msg' => 'Answers have been recorded successfully!',
            'is_passed' => $isUserPassed,
            'correctAnswers' => $correctAnswers,
            'user_roadmap' => $this->roadmapLevelService->getUserCurrentRoadmap(),
        ]);
    }

    public function levelUpUser($test_id, $roadmap_id)
    {
        $getTest = RoadmapTest::query()->with('roadmapLesson')->find($test_id);

        if (!empty($getTest->lesson_id)) {
            $currentLesson = $getTest->roadmapLesson;
            $nextLesson = RoadmapLesson::query()
                ->where('roadmap_id', '=', $roadmap_id)
                ->where('order_id', '>', $currentLesson->order_id)
                ->orderBy('order_id')
                ->first();

            if ($nextLesson) {
                $this->roadmapLevelService->setUserCurrentRoadmapLesson(
                    $nextLesson->roadmap_id,
                    $nextLesson->id,
                );
                $this->updateFinishedRoadmap($roadmap_id);
            }  else {
                /*agar barcha darslarni tugatgan bolsa belgilab qoyamiz, kelajakda yangi dares qoshilganda oshlarga niriktirgani*/
                $this->roadmapLevelService->setRoadmapReachMax();
            }
        }
    }

    public function updateUserStar(Request $request)
    {
        $userId = Auth::id();
        $check_if_user_already_has = $this->roadmapTestHistory->chechIfUserPassedTest($request, $userId);
        if (!$check_if_user_already_has) {
                $user = Auth::user();
                $user->increment('star');
                $user->save();
        }
    }

    public function updateFinishedRoadmap($roadmap_id)
    {
        $userModel = User::query()->find(Auth::id());
        $additionalInfo = $userModel->additional_info;

        $additionalInfoArray = json_decode($additionalInfo, true);

        if (!isset($additionalInfoArray['finished_roadmap'])) {
            $additionalInfoArray['finished_roadmap'] = [];
        }

        foreach ($additionalInfoArray['finished_roadmap'] as $roadmap) {
            if ($roadmap['roadmap_id'] == $roadmap_id) {
                return;
            }
        }
        $additionalInfoArray['finished_roadmap'][] = [
            'roadmap_id' => $roadmap_id,
        ];
        $userModel->additional_info = json_encode($additionalInfoArray);
        $userModel->save();
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

    public function getUserTests($userId, $periodStart, $periodEnd)
    {
        $passedDates = RoadmapUserTestHistory::query()
            ->where('is_passed', true)
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$periodStart, \Carbon\Carbon::createFromFormat('Y-m-d', $periodEnd)->addDay()])
            ->get()
            ->map(fn($item) => $item->created_at->format('Y-m-d'))
            ->toArray();

        $period = \Carbon\CarbonPeriod::create($periodStart, $periodEnd);
        $results = [];
        foreach ($period as $carbonDate) {
            $date = $carbonDate->format('Y-m-d');
            $results[$date] = in_array($date, $passedDates);
        }

        return $results;
    }

    public function getWeeklyTestResult($user_id)
    {
        $dayOfWeekMap = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];

        for ($dayOfWeek = 0; $dayOfWeek <= 6; $dayOfWeek++) {
            $testHistoryExists = RoadmapUserTestHistory::query()
                ->where('is_passed', true)
                ->where('user_id', $user_id)
                ->whereRaw('DAYOFWEEK(created_at) = ?', [($dayOfWeek + 1)])
                ->exists();

            $daysWithRecords[] = [
                'dayOfWeekString' => $dayOfWeekMap[$dayOfWeek],
                'dayOfWeekInteger' => $dayOfWeek,
                'hasPassedTest' => $testHistoryExists
            ];
        }
        return $daysWithRecords;
    }
}
