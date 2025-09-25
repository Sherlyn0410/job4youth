<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id','employer_name','email','password','phoneNo',
        'company_name','company_description','company_logo','company_size','company_type','company_sector',
        'address','city','state','postcode','country',
    ];
    protected $hidden = ['password'];
    protected $casts  = ['password' => 'hashed'];

    public function admin()     { return $this->belongsTo(Admin::class); }
    public function jobPosts()  { return $this->hasMany(JobPost::class); }
    public function applications(){ return $this->hasMany(Application::class); }
}
