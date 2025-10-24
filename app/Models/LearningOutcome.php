<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningOutcome extends Model
{
    use HasFactory;

    protected $fillable = ['subtopic_id', 'outcome', 'difficulty_level'];
}