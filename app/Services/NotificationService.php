<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Stock;
use App\Models\Student;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\StockAlertNotification;
use App\Notifications\PaymentReminderNotification;
use App\Notifications\RepeatedAbsenceNotification;
use App\Notifications\RoomStatusNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Config;

class NotificationService
{
    /**
     * Send stock alert notifications based on stock level percentage.
     *
     * @param \App\Models\Stock $stock
     * @return void
     */
    public function sendStockAlert(Stock $stock)
    {
        // Check if stock notifications are enabled
        if (!Config::get('notifications.stock.enabled', true)) {
            return;
        }
        
        // Get thresholds from config
        $criticalThreshold = Config::get('notifications.stock.critical_threshold', 10);
        $warningThreshold = Config::get('notifications.stock.warning_threshold', 15);
        
        // Calculate percentage of stock remaining
        $percentage = 0;
        if ($stock->maximum_quantity > 0) {
            $percentage = ($stock->quantity / $stock->maximum_quantity) * 100;
        }

        // Determine alert level based on percentage
        $alertLevel = 'normal';
        if ($percentage <= $criticalThreshold) {
            $alertLevel = 'critical';
        } elseif ($percentage <= $warningThreshold) {
            $alertLevel = 'warning';
        }

        // Only send notifications for warning or critical levels
        if ($alertLevel !== 'normal') {
            // Get users with stock management permissions
            $users = User::permission('manage-inventory')->get();
            
            // Send notification to all relevant users
            Notification::send($users, new StockAlertNotification($stock, $alertLevel, $percentage));
        }
    }

    /**
     * Send payment reminder notifications.
     *
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function sendPaymentReminder(Payment $payment)
    {
        // Check if payment notifications are enabled
        if (!Config::get('notifications.payments.enabled', true)) {
            return;
        }
        
        $today = now();
        $dueDate = $payment->due_date;
        
        // Get configuration values
        $upcomingDays = Config::get('notifications.payments.upcoming_days', 7);
        $finalReminderDays = Config::get('notifications.payments.final_reminder_days', 30);
        $smsEnabled = Config::get('notifications.payments.sms_enabled', true) && 
                      Config::get('notifications.channels.sms_enabled', false);
        
        // Determine reminder type based on due date
        $reminderType = 'upcoming';
        $days = 0;
        
        if ($today->gt($dueDate)) {
            // Payment is overdue
            $days = $today->diffInDays($dueDate);
            
            if ($days >= $finalReminderDays) {
                $reminderType = 'final';
            } else {
                $reminderType = 'overdue';
            }
        } else {
            // Payment is upcoming
            $days = $today->diffInDays($dueDate);
            
            // Only send upcoming reminders if within the configured days
            if ($days > $upcomingDays) {
                return;
            }
            
            $reminderType = 'upcoming';
        }
        
        // Get the student/trainee associated with this payment
        $student = $payment->student;
        
        // Also notify finance administrators
        $financeAdmins = User::permission('manage-finances')->get();
        
        // Create notification instance with SMS channel if enabled for final reminders
        $notification = new PaymentReminderNotification($payment, $reminderType, $days);
        
        // Set SMS channel for final reminders if enabled
        if ($reminderType === 'final' && $smsEnabled) {
            $notification->withSms();
        }
        
        // Send notification to the student and finance admins
        if ($student) {
            $student->notify($notification);
        }
        
        Notification::send($financeAdmins, $notification);
    }

    /**
     * Send repeated absence notifications.
     *
     * @param \App\Models\Student $student
     * @param int $absenceCount
     * @param string $period
     * @param bool $unjustified
     * @return void
     */
    public function sendRepeatedAbsenceAlert(Student $student, int $absenceCount, string $period, bool $unjustified = false)
    {
        // Check if absence notifications are enabled
        if (!Config::get('notifications.absences.enabled', true)) {
            return;
        }
        
        // Check if SMS should be enabled for this notification
        $smsEnabled = $unjustified && 
                      Config::get('notifications.absences.sms_enabled', true) && 
                      Config::get('notifications.channels.sms_enabled', false);
        
        // Get instructors and administrators who should be notified
        $instructors = User::permission('manage-absences')->get();
        
        // Create notification instance
        $notification = new RepeatedAbsenceNotification($student, $absenceCount, $period, $unjustified);
        
        // Add SMS channel for unjustified absences if enabled
        if ($smsEnabled) {
            $notification->withSms();
        }
        
        // Send notification to instructors and administrators
        Notification::send($instructors, $notification);
        
        // If the student has a user account, notify them as well
        if ($student->user) {
            $student->user->notify($notification);
        }
    }

    /**
     * Send room status change notifications.
     *
     * @param \App\Models\Room $room
     * @param string $status
     * @param string|null $previousStatus
     * @param string|null $notes
     * @return void
     */
    public function sendRoomStatusUpdate(Room $room, string $status, ?string $previousStatus = null, ?string $notes = null)
    {
        // Check if room notifications are enabled
        if (!Config::get('notifications.rooms.enabled', true)) {
            return;
        }
        
        // Get facility managers who should be notified
        $facilityManagers = User::permission('manage-facilities')->get();
        
        // Create notification instance
        $notification = new RoomStatusNotification($room, $status, $previousStatus, $notes);
        
        // Send notification to facility managers
        Notification::send($facilityManagers, $notification);
    }
    
    /**
     * Check all stock items and send alerts for low stock.
     * This can be run as a scheduled task.
     *
     * @return int Number of notifications sent
     */
    public function checkAllStockLevels()
    {
        // Check if stock notifications are enabled
        if (!Config::get('notifications.stock.enabled', true)) {
            return 0;
        }
        
        // Get threshold from config
        $warningThreshold = Config::get('notifications.stock.warning_threshold', 15);
        
        $stocks = Stock::all();
        $notificationCount = 0;
        
        foreach ($stocks as $stock) {
            // Calculate percentage of stock remaining
            $percentage = 0;
            if ($stock->maximum_quantity > 0) {
                $percentage = ($stock->quantity / $stock->maximum_quantity) * 100;
            }
            
            // Only count notifications for warning or critical levels
            if ($percentage <= $warningThreshold) {
                $this->sendStockAlert($stock);
                $notificationCount++;
            }
        }
        
        return $notificationCount;
    }
    
    /**
     * Check all payments and send reminders for upcoming or overdue payments.
     * This can be run as a scheduled task.
     *
     * @return int Number of notifications sent
     */
    public function checkAllPayments()
    {
        // Check if payment notifications are enabled
        if (!Config::get('notifications.payments.enabled', true)) {
            return 0;
        }
        
        $notificationCount = 0;
        $upcomingDays = Config::get('notifications.payments.upcoming_days', 7);
        
        // Check upcoming payments (due in the configured upcoming days)
        $upcomingPayments = Payment::where('status', '!=', 'paid')
            ->whereBetween('due_date', [now(), now()->addDays($upcomingDays)])
            ->get();
            
        foreach ($upcomingPayments as $payment) {
            $this->sendPaymentReminder($payment);
            $notificationCount++;
        }
        
        // Check overdue payments
        $overduePayments = Payment::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->get();
            
        foreach ($overduePayments as $payment) {
            $this->sendPaymentReminder($payment);
            $notificationCount++;
        }
        
        return $notificationCount;
    }
    
    /**
     * Check student absences and send alerts for repeated or unjustified absences.
     * This can be run as a scheduled task.
     *
     * @return int Number of notifications sent
     */
    public function checkStudentAbsences()
    {
        // Check if absence notifications are enabled
        if (!Config::get('notifications.absences.enabled', true)) {
            return 0;
        }
        
        // Get thresholds from config
        $monthlyThreshold = Config::get('notifications.absences.monthly_threshold', 3);
        $unjustifiedThreshold = Config::get('notifications.absences.unjustified_threshold', 2);
        
        $students = Student::all();
        $notificationCount = 0;
        
        foreach ($students as $student) {
            // Check absences in the current month
            $monthlyAbsences = $student->absences()
                ->whereMonth('start_date', now()->month)
                ->whereYear('start_date', now()->year)
                ->count();
                
            if ($monthlyAbsences >= $monthlyThreshold) {
                $this->sendRepeatedAbsenceAlert($student, $monthlyAbsences, 'this month');
                $notificationCount++;
            }
            
            // Check rejected absences
            $unjustifiedAbsences = $student->absences()
                ->where('status', 'rejected')
                ->whereMonth('start_date', now()->month)
                ->whereYear('start_date', now()->year)
                ->count();
                
            if ($unjustifiedAbsences >= $unjustifiedThreshold) {
                $this->sendRepeatedAbsenceAlert($student, $unjustifiedAbsences, 'this month', true);
                $notificationCount++;
            }
        }
        
        return $notificationCount;
    }
}
