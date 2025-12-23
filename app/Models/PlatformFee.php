<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformFee extends Model
{
    protected $fillable = ['fee_percentage', 'description'];

    public static function getCurrentFee()
    {
        return self::first()?->fee_percentage ?? 10.00;
    }
}
