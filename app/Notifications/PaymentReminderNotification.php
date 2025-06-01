<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The payment that needs attention.
     *
     * @var \App\Models\Payment
     */
    protected $payment;

    /**
     * The reminder type (upcoming, overdue, final).
     *
     * @var string
     */
    protected $reminderType;

    /**
     * The days overdue or until due.
     *
     * @var int
     */
    protected $days;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Payment $payment
     * @param string $reminderType
     * @param int $days
     * @return void
     */
    public function __construct(Payment $payment, string $reminderType, int $days)
    {
        $this->payment = $payment;
        $this->reminderType = $reminderType;
        $this->days = $days;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Use SMS channel if phone number is available and it's a final reminder
        if ($this->reminderType === 'final' && !empty($notifiable->phone)) {
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
        $subject = match($this->reminderType) {
            'upcoming' => 'Payment Due Reminder: ' . $this->payment->reference_number,
            'overdue' => 'OVERDUE Payment: ' . $this->payment->reference_number,
            'final' => 'URGENT: Final Payment Notice - ' . $this->payment->reference_number,
            default => 'Payment Reminder: ' . $this->payment->reference_number,
        };

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($this->reminderType === 'upcoming') {
            $message->line('This is a friendly reminder about an upcoming payment:')
                    ->line('**Payment #' . $this->payment->reference_number . ' is due in ' . $this->days . ' days.**')
                    ->line('Amount: ' . number_format($this->payment->amount, 2) . ' ' . $this->payment->currency)
                    ->line('Due Date: ' . $this->payment->due_date->format('F j, Y'));
        } elseif ($this->reminderType === 'overdue') {
            $message->line('**IMPORTANT: Your payment is overdue.**')
                    ->line('Payment #' . $this->payment->reference_number . ' was due ' . $this->days . ' days ago.')
                    ->line('Amount: ' . number_format($this->payment->amount, 2) . ' ' . $this->payment->currency)
                    ->line('Due Date: ' . $this->payment->due_date->format('F j, Y'))
                    ->line('Please arrange payment as soon as possible to avoid additional late fees.');
        } else {
            $message->line('**URGENT: FINAL PAYMENT NOTICE**')
                    ->line('Payment #' . $this->payment->reference_number . ' is now ' . $this->days . ' days overdue.')
                    ->line('Amount: ' . number_format($this->payment->amount, 2) . ' ' . $this->payment->currency)
                    ->line('Due Date: ' . $this->payment->due_date->format('F j, Y'))
                    ->line('This is your final notice. Failure to pay may result in administrative action.')
                    ->line('If you are experiencing difficulties, please contact our finance department immediately.');
        }

        return $message->action('View Payment Details', route('payments.show', $this->payment->id))
                       ->line('Thank you for your prompt attention to this matter.')
                       ->salutation('Regards, IntraGest Finance Department');
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\VonageMessage
     */
    public function toVonage($notifiable)
    {
        $content = "URGENT: Payment #" . $this->payment->reference_number . " is " . $this->days . " days overdue. Amount: " . 
                  number_format($this->payment->amount, 2) . " " . $this->payment->currency . ". Please pay immediately or contact IntraGest Finance.";
        
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
        $title = match($this->reminderType) {
            'upcoming' => 'Payment Due Soon',
            'overdue' => 'Payment Overdue',
            'final' => 'URGENT: Final Payment Notice',
            default => 'Payment Reminder',
        };

        $message = match($this->reminderType) {
            'upcoming' => 'Payment #' . $this->payment->reference_number . ' of ' . number_format($this->payment->amount, 2) . ' ' . $this->payment->currency . ' is due in ' . $this->days . ' days.',
            'overdue' => 'Payment #' . $this->payment->reference_number . ' of ' . number_format($this->payment->amount, 2) . ' ' . $this->payment->currency . ' is ' . $this->days . ' days overdue.',
            'final' => 'FINAL NOTICE: Payment #' . $this->payment->reference_number . ' of ' . number_format($this->payment->amount, 2) . ' ' . $this->payment->currency . ' is ' . $this->days . ' days overdue.',
            default => 'Payment reminder for #' . $this->payment->reference_number,
        };

        return [
            'title' => $title,
            'message' => $message,
            'icon' => match($this->reminderType) {
                'upcoming' => 'info',
                'overdue' => 'warning',
                'final' => 'error',
                default => 'info',
            },
            'color' => match($this->reminderType) {
                'upcoming' => 'blue',
                'overdue' => 'yellow',
                'final' => 'red',
                default => 'blue',
            },
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'currency' => $this->payment->currency,
            'due_date' => $this->payment->due_date->format('Y-m-d'),
            'days' => $this->days,
            'reminder_type' => $this->reminderType,
            'action_url' => route('payments.show', $this->payment->id),
        ];
    }
}
