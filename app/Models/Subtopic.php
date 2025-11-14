<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    protected $fillable = [
        'topic_id',
        'subtopic_code',
        'subtopic_name',
        'description',
    ];

    /**
     * Polymorphic resources (Resource model uses resourceable_type/resourceable_id)
     */
    public function resources()
    {
        return $this->morphMany(\App\Models\Resource::class, 'resourceable');
    }

    /**
     * Learning outcomes for this subtopic
     */
    public function learningOutcomes()
    {
        return $this->hasMany(\App\Models\LearningOutcome::class, 'subtopic_id');
    }
}