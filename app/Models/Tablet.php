<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tablet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'date',
        'is_active',
        'current_address',
        'driver_id',
    ];

    protected $dates = ['deleted_at'];

    public function driver()
    {
        return $this->belongsTo(Driver::class)->withTrashed();
    }
}
