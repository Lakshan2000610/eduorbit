<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningOutcome extends Model
{
    protected $fillable = [
        'subtopic_id',
        'outcome',
        'difficulty_level',
        // add other columns if present
    ];

    // optional: cast difficulty_level or timestamps if needed
    // protected $casts = ['difficulty_level' => 'string'];
}