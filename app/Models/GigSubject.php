<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigSubject extends Model
{
    protected $fillable = ['gig_id', 'subject_id'];

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topics()
    {
        return $this->hasMany(GigTopic::class);
    }
}