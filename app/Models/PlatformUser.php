<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformUser extends Model
{
    use SoftDeletes;

    protected $table = 'platform_users';

    protected $fillable = [
        'fullname',
        'phone',
    ];
}
