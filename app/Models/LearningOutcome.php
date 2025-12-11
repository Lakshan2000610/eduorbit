<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningOutcome extends Model
{
    protected $fillable = ['subtopic_id', 'outcome', 'difficulty_level'];

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }
}