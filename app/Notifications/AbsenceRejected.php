<?php

namespace App\Notifications;

use App\Models\Absence;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbsenceRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $absence;

    /**
     * Create a new notification instance.
     *
     * @param Absence $absence
     * @return void
     */
    public function __construct(Absence $absence)
    {
        $this->absence = $absence;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $startDate = $this->absence->start_date->format('M d, Y');
        $endDate = $this->absence->end_date->format('M d, Y');
        $duration = $this->absence->getDurationInDays();
        
        return (new MailMessage)
            ->subject('Absence Request Rejected')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your absence request has been rejected.')
            ->line('Details:')
            ->line('- Type: ' . ucfirst($this->absence->type))
            ->line('- Period: ' . $startDate . ' to ' . $endDate . ' (' . $duration . ' day(s))')
            ->line('- Reason: ' . $this->absence->reason)
            ->line('- Rejection reason: ' . ($this->absence->notes ?? 'No reason provided'))
            ->action('View Details', url('/absences/' . $this->absence->id))
            ->line('If you have any questions, please contact the administration.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'absence_id' => $this->absence->id,
            'type' => 'absence_rejected',
            'message' => 'Your absence request from ' . $this->absence->start_date->format('M d, Y') . ' to ' . $this->absence->end_date->format('M d, Y') . ' has been rejected.',
            'rejection_reason' => $this->absence->notes,
            'approver_id' => $this->absence->approver_id,
            'approver_name' => $this->absence->approver ? $this->absence->approver->name : 'Administrator',
        ];
    }
}
