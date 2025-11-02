<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'code',
        'name',
    ];


    public function employmentResponses()
    {
        return $this->hasMany(EmploymentSurveyResponse::class, 'city_work_id');
    }
}
