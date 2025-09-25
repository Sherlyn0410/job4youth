<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // Specify the table name
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
        'skills',
        'status',
        'posted_date',
        'job_view'
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'salary_display' => 'boolean',
        'posted_date' => 'datetime',
        'job_view' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope for open/active jobs only
    public function scopeActive($query)
    {
        return $query->where('status', 'open');
    }

    // Relationship with employer
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    // Relationship with admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Relationship with applications
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_post_id');
    }

    // Accessor for formatted salary range
    public function getSalaryRangeAttribute()
    {
        if (!$this->salary_display || (!$this->salary_min && !$this->salary_max)) {
            return null;
        }

        if ($this->salary_min && $this->salary_max) {
            return 'RM ' . number_format($this->salary_min) . ' - RM ' . number_format($this->salary_max);
        } elseif ($this->salary_min) {
            return 'From RM ' . number_format($this->salary_min);
        } elseif ($this->salary_max) {
            return 'Up to RM ' . number_format($this->salary_max);
        }

        return null;
    }

    // Accessor for company name (from employer relationship)
    public function getCompanyAttribute()
    {
        return $this->employer ? $this->employer->company_name : 'Company';
    }

    // Increment job view count
    public function incrementViews()
    {
        $this->increment('job_view');
    }

    // Helper method to get application count
    public function getApplicationCountAttribute()
    {
        return $this->applications()->count();
    }
}