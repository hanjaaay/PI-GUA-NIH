<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReview extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $attraction = $this->review->touristAttraction->name;
        $user = $this->review->user->name;
        $rating = $this->review->rating;

        return (new MailMessage)
            ->subject("New Review for {$attraction}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("{$user} has left a new review for {$attraction}.")
            ->line("Rating: {$rating} stars")
            ->line("Comment: {$this->review->comment}")
            ->action('View Review', url('/attractions/'.$this->review->tourist_attraction_id))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'review_id' => $this->review->id,
            'user_name' => $this->review->user->name,
            'attraction_name' => $this->review->touristAttraction->name,
            'rating' => $this->review->rating,
            'message' => "{$this->review->user->name} has left a new review for {$this->review->touristAttraction->name}",
        ];
    }
}
