<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'verification_token',
        'reset_password_token',
        'reset_password_expires'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'reset_password_expires' => 'datetime'
    ];
    protected $with = ['userProfile'];
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function userExperiences()
    {
        return $this->hasMany(UserExperience::class);
    }

    public function userEducations()
    {
        return $this->hasMany(UserEducation::class);
    }

    public function userPortfolios()
    {
        return $this->hasMany(UserPortfolio::class);
    }
}
