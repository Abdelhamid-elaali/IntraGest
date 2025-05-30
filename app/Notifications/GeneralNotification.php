<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The notification subject.
     *
     * @var string
     */
    protected $subject;

    /**
     * The notification message.
     *
     * @var string
     */
    protected $message;

    /**
     * The notification action text.
     *
     * @var string|null
     */
    protected $actionText;

    /**
     * The notification action URL.
     *
     * @var string|null
     */
    protected $actionUrl;

    /**
     * Create a new notification instance.
     *
     * @param string $subject
     * @param string $message
     * @param string|null $actionText
     * @param string|null $actionUrl
     * @return void
     */
    public function __construct($subject, $message, $actionText = null, $actionUrl = null)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject($this->subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->message);

        if ($this->actionText && $this->actionUrl) {
            $mail->action($this->actionText, $this->actionUrl);
        }

        return $mail->line('Thank you for using IntraGest!')
            ->salutation('Regards, IntraGest Support Team');
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
            'subject' => $this->subject,
            'message' => $this->message,
            'action_text' => $this->actionText,
            'action_url' => $this->actionUrl,
        ];
    }
}
