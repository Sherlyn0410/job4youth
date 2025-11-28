<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'employers';

    protected $fillable = [
        'admin_id',
        'employer_name',
        'email',
        'password',
        'phoneNo',
        'company_name',
        'company_description',
        'company_logo',
        'company_size',
        'company_type',
        'company_sector',
        'address',
        'city',
        'state',
        'postcode',
        'country'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Get company initial for avatar
    public function getInitialAttribute()
    {
        $name = $this->company_name ?: $this->employer_name;
        return strtoupper(substr($name, 0, 1));
    }

    // Get display name
    public function getDisplayNameAttribute()
    {
        return $this->company_name ?: $this->employer_name;
    }

    // Get employer's logo URL
    public function getLogoUrlAttribute()
    {
        if ($this->company_logo) {
            return asset('storage/company_logos/' . $this->company_logo);
        }
        return null;
    }

    // Relationship with jobs (if you have a jobs table)
    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id');
    }
}
