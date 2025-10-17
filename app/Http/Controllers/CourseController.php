<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // show all courses
    public function index(Request $request)
    {
        $query = Course::query();

        // Filter by type (tab)
        if ($request->has('type') && $request->query('type') !== '') {
            $query->where('type', $request->query('type'));
        }

        // Search query
        if ($request->has('query') && $request->query('query') !== '') {
            $query->where('title', 'like', '%' . $request->query('query') . '%');
        }

        $courses = $query->get();
        $courseTypes = Course::select('type')->distinct()->pluck('type');

        return view('skill-development', compact('courses', 'courseTypes'));
    }



    public function search(Request $request)
    {
        $query = $request->input('query');
        $level = $request->input('level');
        $price = $request->input('price');
        $learningHours = $request->input('learning_hours');

        $courses = Course::query();

        if ($query) {
            $courses->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($level) {
            $courses->where('level', $level);
        }

        if ($price) {
            [$min, $max] = explode('-', $price);
            $courses->whereBetween('price', [(float)$min, (float)$max]);
        }

        if ($learningHours) {
            [$minHours, $maxHours] = explode('-', $learningHours);
            $courses->whereBetween('learning_hours', [(int)$minHours, (int)$maxHours]);
        }

        $courses = $courses->get();

        // Get all unique types from the filtered courses
        $courseTypes = $courses->pluck('type')->unique();

        return view('skill-development', compact('courses', 'courseTypes'));
    }



    public function show($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        return view('skill-development-show', compact('course'));
    }

    public function learningActivities(Request $request)
    {
        $user = $request->user();
        $courses = $user ? $user->courses()->latest('pivot_purchased_at')->get() : collect();
        return view('learning-activities', compact('courses'));
    }
}
