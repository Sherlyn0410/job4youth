<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleCourseEnrollmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user and some courses
        $user = \App\Models\User::first();
        $courses = \App\Models\Course::take(5)->get();
        
        if ($user && $courses->count() > 0) {
            // Create sample enrollments with different progress levels
            $enrollments = [
                [
                    'course_id' => $courses[0]->id,
                    'progress_percentage' => 100,
                    'completed_hours' => $courses[0]->learning_hours,
                    'is_completed' => true,
                    'completed_at' => now()->subDays(5),
                    'last_accessed_at' => now()->subDays(3),
                ],
                [
                    'course_id' => $courses[1]->id,
                    'progress_percentage' => 75,
                    'completed_hours' => round($courses[1]->learning_hours * 0.75),
                    'is_completed' => false,
                    'completed_at' => null,
                    'last_accessed_at' => now()->subHours(2),
                ],
                [
                    'course_id' => $courses[2]->id,
                    'progress_percentage' => 30,
                    'completed_hours' => round($courses[2]->learning_hours * 0.30),
                    'is_completed' => false,
                    'completed_at' => null,
                    'last_accessed_at' => now()->subDays(1),
                ],
                [
                    'course_id' => $courses[3]->id,
                    'progress_percentage' => 0,
                    'completed_hours' => 0,
                    'is_completed' => false,
                    'completed_at' => null,
                    'last_accessed_at' => null,
                ],
                [
                    'course_id' => $courses[4]->id,
                    'progress_percentage' => 100,
                    'completed_hours' => $courses[4]->learning_hours,
                    'is_completed' => true,
                    'completed_at' => now()->subDays(10),
                    'last_accessed_at' => now()->subDays(8),
                ],
            ];
            
            foreach ($enrollments as $enrollment) {
                $user->courses()->attach($enrollment['course_id'], [
                    'purchased_at' => now()->subDays(rand(10, 30)),
                    'progress_percentage' => $enrollment['progress_percentage'],
                    'completed_hours' => $enrollment['completed_hours'],
                    'is_completed' => $enrollment['is_completed'],
                    'completed_at' => $enrollment['completed_at'],
                    'last_accessed_at' => $enrollment['last_accessed_at'],
                ]);
            }
            
            $this->command->info('Sample course enrollments created for user: ' . $user->name);
        } else {
            $this->command->error('No users or courses found. Please create some users and courses first.');
        }
    }
}
