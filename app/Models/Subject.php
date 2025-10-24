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
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function resources()
{
    return $this->morphMany(Resource::class, 'resourceable');
}


}