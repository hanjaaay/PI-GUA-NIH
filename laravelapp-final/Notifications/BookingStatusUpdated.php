<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->booking->status);
        $attraction = $this->booking->touristAttraction->name;

        return (new MailMessage)
            ->subject("Booking Status Updated - {$attraction}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your booking for {$attraction} has been {$status}.")
            ->line('Booking Details:')
            ->line('- Visit Date: '.$this->formatVisitDate())
            ->line("- Number of Tickets: {$this->booking->quantity}")
            ->line('- Total Price: IDR '.number_format($this->booking->total_price ?? 0))
            ->action('View Booking', url('/bookings/'.$this->booking->id))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'status' => $this->booking->status,
            'attraction_name' => $this->booking->touristAttraction->name,
            'visit_date' => $this->booking->visit_date,
            'message' => "Your booking for {$this->booking->touristAttraction->name} has been {$this->booking->status}",
        ];
    }

    /**
     * Safely format the visit date
     */
    private function formatVisitDate(): string
    {
        if (! $this->booking->visit_date) {
            return 'Not set';
        }

        try {
            // Ensure we have a Carbon instance
            if (is_string($this->booking->visit_date)) {
                $date = \Carbon\Carbon::parse($this->booking->visit_date);
            } else {
                $date = $this->booking->visit_date;
            }

            return $date->format('d M Y');
        } catch (\Exception $e) {
            return 'Invalid date';
        }
    }
}
