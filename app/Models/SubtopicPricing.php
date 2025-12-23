<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtopicPricing extends Model
{
    protected $fillable = ['subtopic_id', 'min_price', 'max_price', 'currency'];

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }
}
