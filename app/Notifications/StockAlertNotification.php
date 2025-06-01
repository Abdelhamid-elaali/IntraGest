<?php

namespace App\Notifications;

use App\Models\Stock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The stock item that triggered the alert.
     *
     * @var \App\Models\Stock
     */
    protected $stock;

    /**
     * The alert level (critical, warning, normal).
     *
     * @var string
     */
    protected $alertLevel;

    /**
     * The percentage of stock remaining.
     *
     * @var float
     */
    protected $percentage;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Stock $stock
     * @param string $alertLevel
     * @param float $percentage
     * @return void
     */
    public function __construct(Stock $stock, string $alertLevel, float $percentage)
    {
        $this->stock = $stock;
        $this->alertLevel = $alertLevel;
        $this->percentage = $percentage;
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
        $subject = match($this->alertLevel) {
            'critical' => '🚨 CRITICAL: Stock Alert for ' . $this->stock->name,
            'warning' => '⚠️ WARNING: Low Stock Alert for ' . $this->stock->name,
            default => 'Stock Update: ' . $this->stock->name,
        };

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a stock level notification for:')
            ->line('**' . $this->stock->name . '**');

        if ($this->alertLevel === 'critical') {
            $message->line('**CRITICAL ALERT:** Stock level is critically low at ' . number_format($this->percentage, 1) . '% remaining.')
                    ->line('Current quantity: ' . $this->stock->current_quantity . ' ' . $this->stock->unit)
                    ->line('Minimum required: ' . $this->stock->min_quantity . ' ' . $this->stock->unit)
                    ->action('Order More Stock', route('stock-orders.create', ['stock_id' => $this->stock->id]));
        } elseif ($this->alertLevel === 'warning') {
            $message->line('**WARNING:** Stock level is running low at ' . number_format($this->percentage, 1) . '% remaining.')
                    ->line('Current quantity: ' . $this->stock->current_quantity . ' ' . $this->stock->unit)
                    ->line('Minimum required: ' . $this->stock->min_quantity . ' ' . $this->stock->unit)
                    ->action('View Stock Details', route('stocks.show', $this->stock->id));
        } else {
            $message->line('Stock level is at ' . number_format($this->percentage, 1) . '% remaining.')
                    ->line('Current quantity: ' . $this->stock->current_quantity . ' ' . $this->stock->unit)
                    ->action('View Stock Details', route('stocks.show', $this->stock->id));
        }

        return $message->line('Thank you for using IntraGest!')
                       ->salutation('Regards, IntraGest Inventory Management');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title = match($this->alertLevel) {
            'critical' => 'Critical Stock Alert',
            'warning' => 'Low Stock Warning',
            default => 'Stock Update',
        };

        $message = match($this->alertLevel) {
            'critical' => $this->stock->name . ' is critically low (' . number_format($this->percentage, 1) . '%). Immediate action required.',
            'warning' => $this->stock->name . ' is running low (' . number_format($this->percentage, 1) . '%). Consider reordering soon.',
            default => $this->stock->name . ' is at ' . number_format($this->percentage, 1) . '% of capacity.',
        };

        return [
            'title' => $title,
            'message' => $message,
            'icon' => match($this->alertLevel) {
                'critical' => 'error',
                'warning' => 'warning',
                default => 'info',
            },
            'color' => match($this->alertLevel) {
                'critical' => 'red',
                'warning' => 'yellow',
                default => 'green',
            },
            'stock_id' => $this->stock->id,
            'percentage' => $this->percentage,
            'alert_level' => $this->alertLevel,
            'action_url' => route('stocks.show', $this->stock->id),
        ];
    }
}
