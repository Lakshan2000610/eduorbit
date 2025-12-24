<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigSubtopic extends Model
{
    protected $fillable = ['gig_topic_id', 'subtopic_id', 'duration', 'price']; // ADDED: 'price'

    public function gigTopic()
    {
        return $this->belongsTo(GigTopic::class);
    }

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }
}