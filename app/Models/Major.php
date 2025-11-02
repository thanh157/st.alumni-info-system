<?php
// File: app/Models/Major.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Major extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'major';

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];


    public function students()
    {
        return $this->hasMany(Student::class, 'training_industry_id');
    }


    public function employmentResponses()
    {
        return $this->hasMany(EmploymentSurveyResponse::class, 'training_industry_id');
    }

    public function alumni()
    {
        return $this->hasMany(AlumniContactSurvey::class, 'major_name', 'name');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    public function getStatusTextAttribute()
    {
        return $this->status == 1 ? 'Hoạt động' : 'Không hoạt động';
    }
}
