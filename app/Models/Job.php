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
        'skills',
        'status',
        'posted_date',
        'job_view'
    ];

    protected $casts = [
        'salary_display' => 'boolean',
        'posted_date' => 'datetime',
        'skills' => 'array', // This ensures skills is always an array
    ];

    // Add an accessor to safely handle skills
    public function getSkillsAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        
        if (is_string($value)) {
            // Clean up malformed JSON strings
            $cleanValue = $value;
            
            // Remove escaped quotes and fix malformed JSON
            $cleanValue = str_replace('\\"', '"', $cleanValue);
            $cleanValue = preg_replace('/^"(.*)"$/', '$1', $cleanValue); // Remove outer quotes
            $cleanValue = trim($cleanValue);
            
            // Try to parse as JSON
            if (str_starts_with($cleanValue, '[') && str_ends_with($cleanValue, ']')) {
                $decoded = json_decode($cleanValue, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return array_filter($decoded, function($skill) {
                        return !empty(trim($skill));
                    });
                }
            }
            
            // Handle comma-separated string
            if (str_contains($cleanValue, ',')) {
                return array_filter(array_map('trim', explode(',', $cleanValue)));
            }
            
            // Single skill as string
            if (!empty(trim($cleanValue))) {
                return [trim($cleanValue)];
            }
        }
        
        return is_array($value) ? array_filter($value) : [];
    }

    // Add a mutator to ensure skills are stored as JSON
    public function setSkillsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['skills'] = json_encode($value);
        } elseif (is_string($value)) {
            // If it's already a JSON string, store as is
            $this->attributes['skills'] = $value;
        } else {
            $this->attributes['skills'] = json_encode([]);
        }
    }

    // Relationship with employer
    public function employer()
    {
        return $this->belongsTo(\App\Models\Employer::class, 'employer_id');
    }

    // Relationship with applications
    public function applications()
    {
        return $this->hasMany(\App\Models\Application::class, 'job_post_id');
    }

    // Add back the active scope to filter only active/open jobs
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'open', 'published']);
    }

    // Increment job views
    public function incrementViews()
    {
        $this->increment('job_view');
    }
}

