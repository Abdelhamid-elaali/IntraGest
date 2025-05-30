<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepeatedAbsenceAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $student;
    protected $absenceCount;

    /**
     * Create a new notification instance.
     *
     * @param Student $student
     * @param int $absenceCount
     * @return void
     */
    public function __construct(Student $student, int $absenceCount)
    {
        $this->student = $student;
        $this->absenceCount = $absenceCount;
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
        return (new MailMessage)
            ->subject('Repeated Absence Alert: ' . $this->student->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is an automated alert regarding repeated absences.')
            ->line($this->student->name . ' has recorded ' . $this->absenceCount . ' absences in the last 30 days.')
            ->line('This frequency of absences may require attention according to our absence management policy.')
            ->action('View Student Absences', url('/absences?student_id=' . $this->student->id))
            ->line('Please review the student\'s absence history and take appropriate action if necessary.');
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
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'type' => 'repeated_absence_alert',
            'message' => $this->student->name . ' has recorded ' . $this->absenceCount . ' absences in the last 30 days.',
            'absence_count' => $this->absenceCount,
        ];
    }
}
