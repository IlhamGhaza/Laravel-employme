<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEducation extends Model
{
    /** @use HasFactory<\Database\Factories\UserEducationFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'institution',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'grade',
        'description',
        'is_deleted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
