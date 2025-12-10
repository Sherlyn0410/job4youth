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
        //Converts JSON strings to arrays
        'soft_skills' => 'array',
        'hard_skills' => 'array',
    ];

    /**
     * Scope a query to only include active jobs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'open')
                    ->whereNotNull('posted_date')
                    ->where('posted_date', '<=', now());
    }

    /**
     * Scope a query to only include pending jobs.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include closed jobs.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Get the employer that owns the job.
     */
    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    /**
     * Get the admin that manages the job.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the applications for this job.
     */
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_post_id');
    }

    /**
     * Check if the job is active.
     */
    public function isActive()
    {
        return $this->status === 'open' && 
               $this->posted_date && 
               $this->posted_date <= now();
    }

    /**
     * Increment job view count.
     */
    public function incrementViews()
    {
        $this->increment('job_view');
    }

    /**
     * Get all skills as a merged array of soft and hard skills.
     */
    public function getSkillsAttribute()
    {
        $softSkills = is_array($this->soft_skills) ? $this->soft_skills : [];
        $hardSkills = is_array($this->hard_skills) ? $this->hard_skills : [];
        
        return array_merge($softSkills, $hardSkills);
    }
}

