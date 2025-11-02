<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniContactSurvey extends Model
{
    use HasFactory;

    protected $table = 'alumni_contact_surveys';

    protected $fillable = [
        'survey_batch_id',
        'student_code',
        'full_name',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'ethnicity',
        'nationality',
        'address',
        'phone',
        'email',
        'facebook',
        'instagram',
        'course',
        'class_name',
        'faculty_name',
        'major_name',
        'training_system',
        'level_intermediate',
        'level_college',
        'level_bachelor',
        'level_master',
        'level_phd',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'employment_status',
        'position',
        'awards',
        'connection_status',
        'connection_group',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'level_intermediate' => 'boolean',
        'level_college' => 'boolean',
        'level_bachelor' => 'boolean',
        'level_master' => 'boolean',
        'level_phd' => 'boolean',
        'connection_group' => 'array',
    ];


    public function surveyBatch()
    {
        return $this->belongsTo(ContactSurveyBatch::class, 'survey_batch_id');
    }


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_code', 'code');
    }


    public function getGenderTextAttribute()
    {
        return $this->gender === 'female' ? 'Nữ' : ($this->gender === 'male' ? 'Nam' : '');
    }


    public function getEmploymentStatusTextAttribute()
    {
        $statuses = [
            'working' => 'Đang công tác',
            'retired' => 'Nghỉ hưu',
            'other' => 'Khác',
        ];

        return $statuses[$this->employment_status] ?? '';
    }


    public function getConnectionStatusTextAttribute()
    {
        $statuses = [
            'not_connected' => 'Chưa kết nối',
            'connected' => 'Đã kết nối',
        ];

        return $statuses[$this->connection_status] ?? '';
    }


    public function getCompletedLevelsAttribute()
    {
        $levels = [];

        if ($this->level_intermediate) $levels[] = 'Trung cấp';
        if ($this->level_college) $levels[] = 'Cao đẳng';
        if ($this->level_bachelor) $levels[] = 'Đại học';
        if ($this->level_master) $levels[] = 'Thạc sĩ';
        if ($this->level_phd) $levels[] = 'Tiến sĩ';

        return $levels;
    }
}
