<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
        'language',
        'subject_name',
        'subject_code',
        'status',
        'description',
        'parent_subject_id',
        'is_subsubject',
    ];

    protected $casts = [
        'is_subsubject' => 'boolean',
    ];

    public function topics()
    {
        return $this->hasMany(\App\Models\Topic::class, 'subject_id');
    }

    public function resources()
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

    // parent main subject (if this is a subsubject)
    public function parent()
    {
        return $this->belongsTo(Subject::class, 'parent_subject_id');
    }

    // children subsubjects (if this is a main subject)
    public function children()
    {
        return $this->hasMany(Subject::class, 'parent_subject_id');
    }
}