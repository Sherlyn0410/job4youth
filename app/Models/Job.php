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
        'title',
        'job_overview',
        'responsibilities',
        'requirements',
        'skills',
        'job_type',
        'location',
        'salary_min',
        'salary_max',
        'salary_display',
        'education_level',
        'experience_level',
        'specialization',
        'application_deadline',
        'status',
        'featured'
    ];

    protected $casts = [
        'responsibilities' => 'array',
        'requirements' => 'array',
        'skills' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'salary_display' => 'boolean',
        'application_deadline' => 'date',
        'featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with employer
    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    // Relationship with applications
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_post_id');
    }

    // Scope for active jobs
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for featured jobs
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    // Get formatted salary range
    public function getSalaryRangeAttribute()
    {
        if (!$this->salary_display) {
            return 'Undisclosed';
        }

        if ($this->salary_min && $this->salary_max) {
            return 'RM ' . number_format($this->salary_min) . ' - RM ' . number_format($this->salary_max);
        } elseif ($this->salary_min) {
            return 'From RM ' . number_format($this->salary_min);
        } elseif ($this->salary_max) {
            return 'Up to RM ' . number_format($this->salary_max);
        }

        return 'Negotiable';
    }

    // Check if job is still active for applications
    public function isApplicationOpen()
    {
        if ($this->application_deadline) {
            return $this->application_deadline >= now() && $this->status === 'active';
        }
        return $this->status === 'active';
    }

    // Get days since posted
    public function getDaysAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}