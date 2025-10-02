<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $table = 'employment_survey_responses_v2';

    protected $fillable = [
        'survey_id',
        'student_id',
        'ma_sv',
        'ho_ten',
        'email',
        'phone',
        'student_info',
        'submitted_at',
        'employment_status'
    ];

    protected $casts = [
        'student_info' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
