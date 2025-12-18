<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gig extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'grade',
        'languages',
        'session_duration',
        'selected_subjects',
        'selected_topics',
        'selected_subtopics',
        'status',
        'teacher_id',
    ];

    protected $casts = [
        'languages' => 'array',
        'selected_subjects' => 'array',
        'selected_topics' => 'array',
        'selected_subtopics' => 'array',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function gigs()
    {
        return $this->hasMany(Gig::class, 'teacher_id');
    }
}