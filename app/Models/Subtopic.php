<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    protected $fillable = ['topic_id', 'subtopic_code', 'subtopic_name', 'description'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    /**
     * Polymorphic resources (Resource model uses resourceable_type/resourceable_id)
     */
    public function resources()
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

    /**
     * Learning outcomes for this subtopic
     */
    public function learningOutcomes()
    {
        return $this->hasMany(LearningOutcome::class);
    }

    public function pricing()
{
    return $this->hasOne(SubtopicPricing::class);
}
}