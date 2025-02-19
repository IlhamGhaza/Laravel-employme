<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory ,SoftDeletes;

    protected $fillable = [
        'company_id', 'title', 'salary_min', 'salary_max', 'category', 'location',
        'work_arrangement', 'job_type', 'description', 'requirements', 'responsibilities', 'benefits', 'is_active'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
