<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'full_name',
        'headline',
        'phone',
        'location',
        'about_me',
        'skills',
        'cv_path',
        'linkedin_url',
        'github_url',
        'website_url',
        'applied',
        'reviewed',
        'interview',
        'is_deleted'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
