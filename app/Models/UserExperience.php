<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserExperience extends Model
{
    /** @use HasFactory<\Database\Factories\UserExperienceFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'company_name',
        'title',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'is_deleted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
