<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\GigLanguage;
use App\Models\GigSubject;

class Gig extends Model
{
    protected $fillable = [
        'teacher_id', 'title', 'description', 'grade', 'status'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function languages()
    {
        return $this->hasMany(GigLanguage::class);
    }

    public function subjects()
    {
        return $this->hasMany(GigSubject::class);
    }
}