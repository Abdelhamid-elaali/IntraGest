<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepeatedAbsenceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The student with repeated absences.
     *
     * @var \App\Models\Student
     */
    protected $student;

    /**
     * The number of absences.
     *
     * @var int
     */
    protected $absenceCount;

    /**
     * The period of time (e.g., "this month", "this week").
     *
     * @var string
     */
    protected $period;

    /**
     * Whether the absences are unjustified.
     *
     * @var bool
     */
    protected $unjustified;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Student $student
     * @param int $absenceCount
     * @param string $period
     * @param bool $unjustified
     * @return void
     */
    public function __construct(Student $student, int $absenceCount, string $period, bool $unjustified = false)
    {
        $this->student = $student;
        $this->absenceCount = $absenceCount;
        $this->period = $period;
        $this->unjustified = $unjustified;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // If the absence count is high or they're all unjustified, include SMS
        if (($this->absenceCount > 5 || $this->unjustified) && !empty($notifiable->phone)) {
            return ['mail', 'database', 'vonage'];
        }
        
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
        $subject = $this->unjustified 
            ? 'ALERT: Unjustified Absences - ' . $this->student->name
            : 'Repeated Absence Alert - ' . $this->student->name;

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($this->unjustified) {
            $message->line('**IMPORTANT: Unjustified Absence Alert**')
                    ->line('Student **' . $this->student->name . '** has **' . $this->absenceCount . ' unjustified absences** ' . $this->period . '.')
                    ->line('This requires immediate attention as it violates attendance policy.');
        } else {
            $message->line('This is to inform you about repeated absences:')
                    ->line('Student **' . $this->student->name . '** has been absent **' . $this->absenceCount . ' times** ' . $this->period . '.');
        }

        return $message->line('Student ID: ' . $this->student->student_id)
                       ->line('Class: ' . $this->student->class)
                       ->action('View Absence Details', route('students.absences', $this->student->id))
                       ->line('Please take appropriate action according to the institution\'s attendance policy.')
                       ->salutation('Regards, IntraGest Attendance Management');
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\VonageMessage
     */
    public function toVonage($notifiable)
    {
        $content = $this->unjustified
            ? "ALERT: " . $this->student->name . " has " . $this->absenceCount . " UNJUSTIFIED absences " . $this->period . ". Immediate action required."
            : "Attendance Alert: " . $this->student->name . " has " . $this->absenceCount . " absences " . $this->period . ". Please review.";
        
        return (new \Illuminate\Notifications\Messages\VonageMessage)
            ->content($content)
            ->unicode();
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title = $this->unjustified 
            ? 'Unjustified Absence Alert'
            : 'Repeated Absence Alert';

        $message = $this->unjustified
            ? $this->student->name . ' has ' . $this->absenceCount . ' unjustified absences ' . $this->period . '.'
            : $this->student->name . ' has been absent ' . $this->absenceCount . ' times ' . $this->period . '.';

        return [
            'title' => $title,
            'message' => $message,
            'icon' => $this->unjustified ? 'error' : 'warning',
            'color' => $this->unjustified ? 'red' : 'yellow',
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'absence_count' => $this->absenceCount,
            'period' => $this->period,
            'unjustified' => $this->unjustified,
            'action_url' => route('students.absences', $this->student->id),
        ];
    }
}
