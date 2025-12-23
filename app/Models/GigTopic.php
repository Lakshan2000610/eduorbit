<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigTopic extends Model
{
    protected $fillable = ['gig_subject_id', 'topic_id', 'duration'];

    public function gigSubject()
    {
        return $this->belongsTo(GigSubject::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function subtopics()
    {
        return $this->hasMany(GigSubtopic::class);
    }
}