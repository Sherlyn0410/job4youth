<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_posts';

    protected $fillable = [
        'employer_id',
        'admin_id',
        'title',
        'location',
        'job_type',
        'specialization',
        'education_level',
        'salary_min',
        'salary_max',
        'salary_display',
        'job_overview',
        'responsibilities',
        'requirements',
        'soft_skills',     
        'hard_skills',    
        'status',
        'posted_date',
        'job_view',
    ];

    protected $casts = [
        'salary_display' => 'boolean',
        'posted_date' => 'datetime',
        'soft_skills' => 'array',  
        'hard_skills' => 'array',  
    ];

    /**
     * Get the employer that owns the job.
     */
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    /**
     * Get the admin that manages the job.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}

