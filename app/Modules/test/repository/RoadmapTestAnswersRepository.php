<?php

namespace App\Modules\test\repository;

use App\Modules\test\models\RoadmapTestAnswers;

class RoadmapTestAnswersRepository
{
    private $model;

    public function __construct(RoadmapTestAnswers $model)
    {
        $this->model = $model;
    }
    public function create($data)
    {
        return $this->model->insert($data);
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }
}
