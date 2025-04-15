<?php

namespace App\Modules\test\repository;

use App\Models\Setting;
use App\Modules\test\models\RoadmapTest;
use App\Modules\test\models\RoadmapTestQuestion;
use App\Modules\test\service\RoadmapTestCreateService;

class RoadmapTestQuestionRepository
{
    private $model;

    public function __construct(RoadmapTestQuestion $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update(array $data)
    {
        $model = $this->getById($data['id']);

        if (!$model) {
            return Response(['error' => "Model not found"]);
        }
        $model->update($data);

        return $model;
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getByTestId($test_id)
    {
        return $this->model
            ->where('test_id', $test_id)
            ->with('options');
    }

    public function getLimitedByTestId($test_id)
    {
        $results = $this->model->query()
            ->where('test_id', $test_id)
            ->with(['options' => function ($query) {
                $query->select(['option_text_', 'question_id','is_correct','points']);
            }])
            ->get();

        return $results;
    }


    public function deleteTestQuestion($question_id)
    {
        $question = $this->model->find($question_id);
        if (!$question) throw new \Exception("Test question not found");

        return $question->delete();
    }

}
