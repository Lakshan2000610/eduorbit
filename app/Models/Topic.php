<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'topic_code',
        'topic_name',
        'description',
    ];


    public function subtopics()
{
    return $this->hasMany(Subtopic::class);
}

public function resources()
{
    return $this->morphMany(Resource::class, 'resourceable');
}

}