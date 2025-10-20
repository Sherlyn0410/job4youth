<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin_id',
        'phone_no',
        'state',
        'city',
        'profile_picture',
        'resume',
        'self_intro',
        'skills',
        'education',
        'experience',
        'license_cert_type',
        'language',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the admin that manages this user.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the profile picture URL.
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        return null;
    }

    public function hasAppliedFor($jobId)
    {
        return $this->applications()->where('job_post_id', $jobId)->where('status', '!=', 'withdrawn')->exists();
    }
    
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function courses()
    {
        return $this->belongsToMany(related: Course::class)
            ->withPivot('purchased_at')
            ->withTimestamps();
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }

    // Helper method to check if user has saved a job
    public function hasSavedJob($jobId)
    {
        // Change job_id to job_post_id if that's the correct column name
        return $this->savedJobs()->where('job_post_id', $jobId)->exists();
    }

    // Helper method to save a job
    public function saveJob($jobId)
    {
        // Change job_id to job_post_id if that's the correct column name
        return $this->savedJobs()->firstOrCreate(['job_post_id' => $jobId]);
    }

    // Helper method to unsave a job
    public function unsaveJob($jobId)
    {
        // Change job_id to job_post_id if that's the correct column name
        return $this->savedJobs()->where('job_post_id', $jobId)->delete();
    }
}
