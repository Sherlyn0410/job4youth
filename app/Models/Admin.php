<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','password'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function employers()      { return $this->hasMany(Employer::class); }
    public function jobPosts()       { return $this->hasMany(JobPost::class); }
    public function learningContents(){ return $this->hasMany(LearningContent::class); }
    public function payments()       { return $this->hasMany(Payment::class); }
    public function careerGuidances(){ return $this->hasMany(CareerGuidance::class); }
}
