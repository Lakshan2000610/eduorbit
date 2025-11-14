<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSubjectSelection extends Model
{
    protected $table = 'student_subject_selections';

    protected $fillable = [
        'student_id',
        'grade',
        'language',
        'subject_id',
        'is_current',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    public function subjectSelections()
    {
        return $this->hasMany(\App\Models\StudentSubjectSelection::class, 'student_id');
    }
}
