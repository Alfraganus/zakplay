<?php

namespace App\Models;

use App\Modules\test\models\RoadmapTest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leaderboard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leaderboard';
    const ALL_TEST = 1;
    const SPECIAL_TEST = 2;

    protected $fillable = [
        'name',
        'start_date',
        'finish_date',
        'is_active',
        'test_type',
        'test_id',
    ];

    protected $dates = [
        'start_date',
        'finish_date',
        'deleted_at',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', 1);
        });
    }

    /**
     * Relationship to RoadmapTest
     */
    public function test()
    {
        return $this->belongsTo( RoadmapTest::class, 'test_id');
    }
}
