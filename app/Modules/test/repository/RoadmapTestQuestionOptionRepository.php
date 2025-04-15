<?php

namespace App\Modules\test\repository;

use App\Modules\test\models\RoadmapTestQuestionOption;

class RoadmapTestQuestionOptionRepository
{
    private $model;

    public function __construct(RoadmapTestQuestionOption $model)
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

    public function getByQuestionId($question_id)
    {
        return $this->model::where('question_id', $question_id);
    }


    public function delete($question_id)
    {
        return $this->model->where('question_id', $question_id)->delete();
    }
}
