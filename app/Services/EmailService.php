<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class EmailService
{
    /**
     * Send a notification email to a user.
     *
     * @param User $user
     * @param string $subject
     * @param string $message
     * @param string|null $actionText
     * @param string|null $actionUrl
     * @return void
     */
    public static function sendNotification(User $user, string $subject, string $message, ?string $actionText = null, ?string $actionUrl = null)
    {
        $user->notify(new GeneralNotification($subject, $message, $actionText, $actionUrl));
    }

    /**
     * Send a notification email to multiple users.
     *
     * @param array|Collection $users
     * @param string $subject
     * @param string $message
     * @param string|null $actionText
     * @param string|null $actionUrl
     * @return void
     */
    public static function sendBulkNotification($users, string $subject, string $message, ?string $actionText = null, ?string $actionUrl = null)
    {
        Notification::send($users, new GeneralNotification($subject, $message, $actionText, $actionUrl));
    }

    /**
     * Send a welcome email to a new user.
     *
     * @param User $user
     * @return void
     */
    public static function sendWelcomeEmail(User $user)
    {
        $subject = 'Welcome to IntraGest';
        $message = 'Thank you for joining IntraGest. We are excited to have you on board!';
        $actionText = 'Get Started';
        $actionUrl = url('/dashboard');

        self::sendNotification($user, $subject, $message, $actionText, $actionUrl);
    }

    /**
     * Send a password reset confirmation email.
     *
     * @param User $user
     * @return void
     */
    public static function sendPasswordResetConfirmation(User $user)
    {
        $subject = 'Password Reset Successful';
        $message = 'Your password has been successfully reset. If you did not perform this action, please contact support immediately.';
        $actionText = 'Contact Support';
        $actionUrl = url('/help-center/contact');

        self::sendNotification($user, $subject, $message, $actionText, $actionUrl);
    }

    /**
     * Send a payment confirmation email.
     *
     * @param User $user
     * @param string $paymentId
     * @param float $amount
     * @return void
     */
    public static function sendPaymentConfirmation(User $user, string $paymentId, float $amount)
    {
        $subject = 'Payment Confirmation';
        $message = "Your payment of $" . number_format($amount, 2) . " (Payment ID: $paymentId) has been successfully processed.";
        $actionText = 'View Payment';
        $actionUrl = url("/payments/$paymentId");

        self::sendNotification($user, $subject, $message, $actionText, $actionUrl);
    }

    /**
     * Send a system notification email.
     *
     * @param User $user
     * @param string $title
     * @param string $content
     * @return void
     */
    public static function sendSystemNotification(User $user, string $title, string $content)
    {
        $subject = 'System Notification: ' . $title;
        $message = $content;

        self::sendNotification($user, $subject, $message);
    }
}
