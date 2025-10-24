<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    use HasFactory;

    protected $fillable = ['topic_id', 'subtopic_code', 'subtopic_name', 'description'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function learningOutcomes()
    {
        return $this->hasMany(LearningOutcome::class);
    }
}