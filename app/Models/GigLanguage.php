<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigLanguage extends Model
{
    protected $fillable = ['gig_id', 'language'];

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }
}