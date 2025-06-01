<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class NotifyService
{
    /**
     * Display a stock level notification banner.
     *
     * @param string $itemName
     * @param float $percentage
     * @param string $message
     * @return void
     */
    public function stockLevel(string $itemName, float $percentage, string $message = null)
    {
        $type = 'success';
        $title = 'Stock Level: ' . $itemName;
        
        if ($percentage <= 10) {
            $type = 'error';
            $message = $message ?? 'Critical: Stock level is extremely low at ' . number_format($percentage, 1) . '%';
        } elseif ($percentage <= 15) {
            $type = 'warning';
            $message = $message ?? 'Warning: Stock level is low at ' . number_format($percentage, 1) . '%';
        } else {
            $message = $message ?? 'Stock level is sufficient at ' . number_format($percentage, 1) . '%';
        }
        
        $this->addNotification($type, $title, $message);
    }
    
    /**
     * Display a payment notification banner.
     *
     * @param string $referenceNumber
     * @param string $status
     * @param string $message
     * @return void
     */
    public function payment(string $referenceNumber, string $status, string $message = null)
    {
        $type = 'info';
        $title = 'Payment #' . $referenceNumber;
        
        if ($status === 'overdue') {
            $type = 'warning';
            $message = $message ?? 'Payment is overdue';
        } elseif ($status === 'final') {
            $type = 'error';
            $message = $message ?? 'Final payment notice';
        } elseif ($status === 'paid') {
            $type = 'success';
            $message = $message ?? 'Payment completed successfully';
        } else {
            $message = $message ?? 'Payment is due soon';
        }
        
        $this->addNotification($type, $title, $message);
    }
    
    /**
     * Display an absence notification banner.
     *
     * @param string $studentName
     * @param int $count
     * @param bool $unjustified
     * @param string $message
     * @return void
     */
    public function absence(string $studentName, int $count, bool $unjustified = false, string $message = null)
    {
        $type = 'info';
        $title = 'Absence Alert: ' . $studentName;
        
        if ($unjustified) {
            $type = 'error';
            $message = $message ?? $count . ' unjustified absences recorded';
        } elseif ($count >= 5) {
            $type = 'warning';
            $message = $message ?? $count . ' absences recorded';
        } else {
            $message = $message ?? $count . ' absences recorded';
        }
        
        $this->addNotification($type, $title, $message);
    }
    
    /**
     * Display a room status notification banner.
     *
     * @param string $roomName
     * @param string $status
     * @param string $message
     * @return void
     */
    public function roomStatus(string $roomName, string $status, string $message = null)
    {
        $type = 'info';
        $title = 'Room ' . $roomName;
        
        if ($status === 'available') {
            $type = 'success';
            $message = $message ?? 'Room is available';
        } elseif ($status === 'occupied') {
            $type = 'error';
            $message = $message ?? 'Room is occupied';
        } elseif ($status === 'maintenance') {
            $type = 'warning';
            $message = $message ?? 'Room is under maintenance';
        } else {
            $message = $message ?? 'Room status updated';
        }
        
        $this->addNotification($type, $title, $message);
    }
    
    /**
     * Add a notification to the session.
     *
     * @param string $type
     * @param string $title
     * @param string $message
     * @return void
     */
    private function addNotification(string $type, string $title, string $message)
    {
        $notifications = Session::get('notifications', []);
        
        $notifications[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        Session::put('notifications', $notifications);
    }
    
    /**
     * Clear all notifications from the session.
     *
     * @return void
     */
    public function clearNotifications()
    {
        Session::forget('notifications');
    }
}
