<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Notifications\CoursePurchased;


class CheckoutController extends Controller
{
    public function checkout(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => $course->title,
                        'description' => $course->description,
                    ],
                    'unit_amount' => $course->price * 100, // amount in sen
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['course_id' => $course->id], true)
                . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel', [], true),
        ]);

        return redirect($session->url);
    }

    // public function success()
    // {
    //     return view('stripe.success');
    // }

    public function success(Request $request)
    {
        $courseId = $request->query('course_id');
        $course = Course::findOrFail($courseId);
        $user = $request->user();

        if (!$user->courses()->where('course_id', $courseId)->exists()) {
            $user->courses()->attach($courseId, ['purchased_at' => now()]);
            $user->notify(new CoursePurchased($course));
        }

        return view('stripe.success', ['course' => $course]);
    }


    public function cancel()
    {
        return view('stripe.cancel');
    }
}
