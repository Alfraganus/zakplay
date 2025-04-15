<?php

namespace App\Modules\test\repository;

use App\Modules\test\models\RoadmapUserTestHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoadmapUserTestHistoryRepository
{
    protected $model;

    public function __construct(RoadmapUserTestHistory $model)
    {
        $this->model = $model::query();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function countByUserId()
    {
        return $this->model->where('user_id', Auth::id())->count();
    }

    public function findAllTestHistory($device_id)
    {
        return $this->model->where('device_id', $device_id)->get();
    }

    public function chechIfUserPassedTest(Request $request, $user_id)
    {
        return $this
            ->model
            ->where('user_id', $user_id)
            ->where('test_id', $request->input('test_id'))
            ->where('lesson_id', $request->input('lesson_id'))
            ->first();
    }
}
