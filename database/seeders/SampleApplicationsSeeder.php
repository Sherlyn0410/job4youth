<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\User;
use App\Models\Job;
use App\Models\Employer;

class SampleApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing users, jobs, and employers
        $users = User::limit(10)->get();
        $employers = Employer::limit(5)->get();
        
        if ($users->isEmpty()) {
            echo "No users found. Creating sample users...\n";
            $users = collect([
                User::create([
                    'name' => 'John Doe',
                    'email' => 'john.doe@example.com',
                    'password' => bcrypt('password123'),
                    'phone' => '+601234567890',
                ]),
                User::create([
                    'name' => 'Jane Smith',
                    'email' => 'jane.smith@example.com',
                    'password' => bcrypt('password123'),
                    'phone' => '+601234567891',
                ]),
                User::create([
                    'name' => 'Mike Johnson',
                    'email' => 'mike.johnson@example.com',
                    'password' => bcrypt('password123'),
                    'phone' => '+601234567892',
                ]),
                User::create([
                    'name' => 'Sarah Wilson',
                    'email' => 'sarah.wilson@example.com',
                    'password' => bcrypt('password123'),
                    'phone' => '+601234567893',
                ]),
                User::create([
                    'name' => 'David Brown',
                    'email' => 'david.brown@example.com',
                    'password' => bcrypt('password123'),
                    'phone' => '+601234567894',
                ])
            ]);
        }
        
        if ($employers->isEmpty()) {
            echo "No employers found. Please create some employers first.\n";
            return;
        }
        
        // Get jobs from existing employers
        $jobs = Job::whereIn('employer_id', $employers->pluck('id'))->limit(10)->get();
        
        if ($jobs->isEmpty()) {
            echo "No jobs found. Please create some jobs first.\n";
            return;
        }
        
        // Create sample applications
        $statuses = ['submitted', 'shortlisted', 'accepted', 'rejected'];
        
        foreach ($jobs as $job) {
            // Create 2-4 applications per job
            $applicationCount = rand(2, 4);
            
            for ($i = 0; $i < $applicationCount; $i++) {
                $user = $users->random();
                
                // Check if application already exists (to avoid duplicates)
                $existingApplication = Application::where('user_id', $user->id)
                    ->where('job_post_id', $job->id)
                    ->first();
                    
                if (!$existingApplication) {
                    Application::create([
                        'employer_id' => $job->employer_id,
                        'user_id' => $user->id,
                        'job_post_id' => $job->id,
                        'status' => $statuses[array_rand($statuses)],
                        'apply_date' => now()->subDays(rand(1, 30)),
                        'application_view_time' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                    ]);
                    
                    echo "Created application for {$user->name} -> {$job->title}\n";
                }
            }
        }
        
        echo "Sample applications created successfully!\n";
    }
}
