<?php

namespace App\Notifications;

use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoomStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The room that changed status.
     *
     * @var \App\Models\Room
     */
    protected $room;

    /**
     * The status of the room (available, occupied, maintenance).
     *
     * @var string
     */
    protected $status;

    /**
     * The previous status of the room.
     *
     * @var string|null
     */
    protected $previousStatus;

    /**
     * Additional information about the status change.
     *
     * @var string|null
     */
    protected $notes;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Room $room
     * @param string $status
     * @param string|null $previousStatus
     * @param string|null $notes
     * @return void
     */
    public function __construct(Room $room, string $status, ?string $previousStatus = null, ?string $notes = null)
    {
        $this->room = $room;
        $this->status = $status;
        $this->previousStatus = $previousStatus;
        $this->notes = $notes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = 'Room Status Update: ' . $this->room->name;

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($this->previousStatus) {
            $message->line('Room **' . $this->room->name . '** status has changed from **' . ucfirst($this->previousStatus) . '** to **' . ucfirst($this->status) . '**.');
        } else {
            $message->line('Room **' . $this->room->name . '** is now **' . ucfirst($this->status) . '**.');
        }

        if ($this->notes) {
            $message->line('Notes: ' . $this->notes);
        }

        return $message->action('View Room Details', route('rooms.show', $this->room->id))
                       ->line('Thank you for using IntraGest!')
                       ->salutation('Regards, IntraGest Facility Management');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title = 'Room Status Update';

        $message = $this->previousStatus
            ? 'Room ' . $this->room->name . ' status changed from ' . ucfirst($this->previousStatus) . ' to ' . ucfirst($this->status) . '.'
            : 'Room ' . $this->room->name . ' is now ' . ucfirst($this->status) . '.';

        if ($this->notes) {
            $message .= ' Notes: ' . $this->notes;
        }

        return [
            'title' => $title,
            'message' => $message,
            'icon' => match($this->status) {
                'available' => 'check_circle',
                'occupied' => 'person',
                'maintenance' => 'build',
                default => 'info',
            },
            'color' => match($this->status) {
                'available' => 'green',
                'occupied' => 'red',
                'maintenance' => 'yellow',
                default => 'blue',
            },
            'room_id' => $this->room->id,
            'room_name' => $this->room->name,
            'status' => $this->status,
            'previous_status' => $this->previousStatus,
            'notes' => $this->notes,
            'action_url' => route('rooms.show', $this->room->id),
        ];
    }
}
