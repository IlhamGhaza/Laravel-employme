<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo_path',
        'website',
        'description',
        'industry',
        'location',
        'founded_year'
    ];
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
