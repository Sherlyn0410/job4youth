<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'user_id',
        'job_post_id',
        'status',
        'apply_date',
        'application_view_time'
    ];

    protected $casts = [
        'apply_date' => 'datetime',
        'application_view_time' => 'datetime',
    ];

    // Relationships
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(Job::class, 'job_post_id');
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}