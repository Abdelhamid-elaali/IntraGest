<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for the IntraGest notification system.
    | Adjust these values to control when and how notifications are triggered.
    |
    */

    'stock' => [
        // Stock level thresholds (percentage of max quantity)
        'critical_threshold' => env('STOCK_CRITICAL_THRESHOLD', 10),
        'warning_threshold' => env('STOCK_WARNING_THRESHOLD', 15),
        
        // Enable/disable stock notifications
        'enabled' => env('STOCK_NOTIFICATIONS_ENABLED', true),
    ],
    
    'payments' => [
        // Days before due date to send upcoming payment reminders
        'upcoming_days' => env('PAYMENT_UPCOMING_DAYS', 7),
        
        // Days after due date to send final reminder (with SMS)
        'final_reminder_days' => env('PAYMENT_FINAL_REMINDER_DAYS', 30),
        
        // Enable/disable payment notifications
        'enabled' => env('PAYMENT_NOTIFICATIONS_ENABLED', true),
        
        // Enable/disable SMS for final reminders
        'sms_enabled' => env('PAYMENT_SMS_ENABLED', true),
    ],
    
    'absences' => [
        // Number of absences in a month to trigger notification
        'monthly_threshold' => env('ABSENCE_MONTHLY_THRESHOLD', 3),
        
        // Number of unjustified absences to trigger notification
        'unjustified_threshold' => env('ABSENCE_UNJUSTIFIED_THRESHOLD', 2),
        
        // Enable/disable absence notifications
        'enabled' => env('ABSENCE_NOTIFICATIONS_ENABLED', true),
        
        // Enable/disable SMS for repeated unjustified absences
        'sms_enabled' => env('ABSENCE_SMS_ENABLED', true),
    ],
    
    'rooms' => [
        // Enable/disable room status notifications
        'enabled' => env('ROOM_NOTIFICATIONS_ENABLED', true),
    ],
    
    'channels' => [
        // Default notification channels
        'default' => ['database', 'mail'],
        
        // Enable/disable email notifications globally
        'mail_enabled' => env('MAIL_NOTIFICATIONS_ENABLED', true),
        
        // Enable/disable SMS notifications globally
        'sms_enabled' => env('SMS_NOTIFICATIONS_ENABLED', false),
        
        // Enable/disable database notifications globally
        'database_enabled' => env('DATABASE_NOTIFICATIONS_ENABLED', true),
    ],
];
