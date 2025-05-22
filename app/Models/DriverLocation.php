<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverLocation extends Model
{
    use HasFactory;

    protected $table = 'driver_locations';

    protected $fillable = [
        'driver_id',
        'lat',
        'lon',
    ];


    /**
     * Get the driver that owns the location.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
