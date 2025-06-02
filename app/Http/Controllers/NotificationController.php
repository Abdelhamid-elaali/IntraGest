<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Stock;
use App\Models\Payment;
use App\Models\Absence;
use App\Services\NotifyService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    protected $notifyService;
    protected $notificationService;

    public function __construct(NotifyService $notifyService, NotificationService $notificationService)
    {
        $this->notifyService = $notifyService;
        $this->notificationService = $notificationService;
    }

    /**
     * Display the user's notifications with optional filtering.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = auth()->user()->notifications();
        $user = auth()->user();
        
        // Role-based filtering - Stock managers can only see stock-related notifications
        if ($user->isStockManager() && !$user->isAdmin() && !$user->isSuperAdmin()) {
            $query->where(function($q) {
                $q->whereJsonContains('data->type', 'stock')
                  ->orWhereJsonContains('data->notification_type', 'stock');
            });
        }
        
        // Filter by notification type
        if ($request->has('type') && $request->type) {
            $type = $request->type;
            // For stock managers, ensure they can only filter within stock-related notifications
            if ($user->isStockManager() && !$user->isAdmin() && !$user->isSuperAdmin() && $type !== 'stock') {
                $type = 'stock';
            }
            
            $query->where(function($q) use ($type) {
                $q->whereJsonContains('data->type', $type)
                  ->orWhereJsonContains('data->notification_type', $type);
            });
        }
        
        // Filter by read/unread status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }
        }
        
        // Filter by date range
        if ($request->has('date') && $request->date) {
            $now = now();
            if ($request->date === 'today') {
                $query->whereDate('created_at', $now->toDateString());
            } elseif ($request->date === 'week') {
                $query->where('created_at', '>=', $now->startOfWeek()->toDateTimeString())
                      ->where('created_at', '<=', $now->endOfWeek()->toDateTimeString());
            } elseif ($request->date === 'month') {
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
            }
        }
        
        $notifications = $query->paginate(10)->withQueryString();
        
        // Pass the user role information to the view for UI customization
        $isStockManager = $user->isStockManager() && !$user->isAdmin() && !$user->isSuperAdmin();
        
        return view('notifications.index', compact('notifications', 'isStockManager'));
    }

    /**
     * Display the notification dashboard with summary stats.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = auth()->user();
        $isStockManager = $user->isStockManager() && !$user->isAdmin() && !$user->isSuperAdmin();
        
        // Get counts for dashboard cards
        $criticalStockCount = Stock::whereRaw('quantity <= (maximum_quantity * 0.1)')->count();
        
        $overduePaymentsCount = Payment::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->count();
            
        $unjustifiedAbsencesCount = Absence::where('status', 'rejected')
            ->whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->count();
            
        $availableRoomsCount = Room::where('status', 'available')->count();
        
        // Get recent notifications with role-based filtering
        $notificationsQuery = auth()->user()->notifications();
        
        // Stock managers can only see stock-related notifications
        if ($isStockManager) {
            $notificationsQuery->where(function($q) {
                $q->whereJsonContains('data->type', 'stock')
                  ->orWhereJsonContains('data->notification_type', 'stock');
            });
        }
        
        $notifications = $notificationsQuery->latest()->take(5)->get();
        
        return view('dashboard.notifications', compact(
            'criticalStockCount',
            'overduePaymentsCount',
            'unjustifiedAbsencesCount',
            'availableRoomsCount',
            'notifications',
            'isStockManager'
        ));
    }

    /**
     * Mark a specific notification as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Request $request)
    {
        $notification = auth()->user()->notifications()->findOrFail($request->id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read for the authenticated user.
     * For stock managers, only mark stock-related notifications as read.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();
        $isStockManager = $request->input('isStockManager', false) || 
                         ($user->isStockManager() && !$user->isAdmin() && !$user->isSuperAdmin());
        
        if ($isStockManager) {
            // Only mark stock-related notifications as read for stock managers
            $stockNotifications = $user->unreadNotifications()
                ->where(function($q) {
                    $q->whereJsonContains('data->type', 'stock')
                      ->orWhereJsonContains('data->notification_type', 'stock');
                })
                ->get();
            
            foreach ($stockNotifications as $notification) {
                $notification->markAsRead();
            }
            
            $message = 'All stock notifications marked as read.';
        } else {
            // Mark all notifications as read for other users
            $user->unreadNotifications->markAsRead();
            $message = 'All notifications marked as read.';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    
    /**
     * Get recent notifications for the authenticated user (API endpoint).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentNotifications()
    {
        $user = auth()->user();
        $isStockManager = $user->isStockManager() && !$user->isAdmin() && !$user->isSuperAdmin();
        
        // Build the query for notifications
        $notificationsQuery = $user->notifications();
        $unreadQuery = $user->unreadNotifications();
        
        // Stock managers can only see stock-related notifications
        if ($isStockManager) {
            $notificationsQuery->where(function($q) {
                $q->whereJsonContains('data->type', 'stock')
                  ->orWhereJsonContains('data->notification_type', 'stock');
            });
            
            $unreadQuery->where(function($q) {
                $q->whereJsonContains('data->type', 'stock')
                  ->orWhereJsonContains('data->notification_type', 'stock');
            });
        }
        
        $notifications = $notificationsQuery->latest()->take(5)->get();
        $unreadCount = $unreadQuery->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Run a manual check for stock alerts.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkStockLevels()
    {
        $this->notificationService->checkAllStockLevels();
        
        return redirect()->route('notifications.dashboard')
            ->with('success', 'Stock level check completed. Notifications sent for low stock items.');
    }
    
    /**
     * Run a manual check for payment reminders.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkPayments()
    {
        $this->notificationService->checkAllPayments();
        
        return redirect()->route('notifications.dashboard')
            ->with('success', 'Payment check completed. Reminders sent for upcoming and overdue payments.');
    }
    
    /**
     * Run a manual check for absence alerts.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAbsences()
    {
        $this->notificationService->checkStudentAbsences();
        
        return redirect()->route('notifications.dashboard')
            ->with('success', 'Absence check completed. Alerts sent for students with repeated or unjustified absences.');
    }
}
