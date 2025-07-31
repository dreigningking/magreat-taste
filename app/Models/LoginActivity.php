<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'browser', // the raw user agent string
        'device',
        'os',
        'browser_name',
        'browser_version',
        'platform',
        'platform_version',
        // ... any other fields
    ];
}
