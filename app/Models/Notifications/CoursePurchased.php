<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Course;

class CoursePurchased extends Notification
{
    use Queueable;

    protected $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    // Which channels to send the notification
    public function via($notifiable)
    {
        return ['mail', 'database']; // email + store in DB
    }

    // Email content
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Course Purchased Successfully')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have successfully purchased the course: ' . $this->course->title)
            ->action('Go to Course', url('/skill-development/' . $this->course->slug))
            ->line('Thank you for learning with us!');
    }

    // Store in database
    public function toDatabase($notifiable)
    {
        return [
            'course_id' => $this->course->id,
            'title' => $this->course->title,
            'message' => 'You have successfully enrolled in this course.'
        ];
    }
}
