<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsencesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockStatisticsController;
use App\Http\Controllers\StockAnalyticsController;
use App\Http\Controllers\StockCategoryController;
use App\Http\Controllers\StockOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ErrorController;

// Authentication Routes (will be handled by Laravel Breeze/Fortify)
require __DIR__.'/auth.php';

// Redirect root to login for guests, dashboard for authenticated users
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getUpdatedStats'])->name('dashboard.stats');
    Route::get('/dashboard/trainee-count', [DashboardController::class, 'getTraineeCount'])->name('dashboard.trainee-count');

    // Test Routes
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');
        Route::get('/alert/{alertType}', [TestController::class, 'showAlert'])->name('alert.show');
    });

    // Absence Management
    Route::resource('absences', AbsencesController::class);
    Route::resource('absence-types', 'App\Http\Controllers\AbsenceTypeController');
    
    // Absence Reports - Only register index route, not the full resource
    Route::get('absence-reports', 'App\Http\Controllers\AbsenceReportController@index')->name('absence-reports.index');
    
    Route::prefix('absences')->name('absences.')->group(function () {
        Route::get('reports', [AbsencesController::class, 'reports'])->name('reports');
        Route::post('{absence}/approve', [AbsencesController::class, 'approve'])->name('approve');
        Route::post('{absence}/reject', [AbsencesController::class, 'reject'])->name('reject');
    });
    
    Route::prefix('absence-reports')->name('absence-reports.')->group(function () {
        Route::get('monthly', 'App\Http\Controllers\AbsenceReportController@monthly')->name('monthly');
        Route::get('by-type', 'App\Http\Controllers\AbsenceReportController@byType')->name('by-type');
        Route::get('by-student', 'App\Http\Controllers\AbsenceReportController@byStudent')->name('by-student');
    });

    // Payment Management
    Route::resource('payments', PaymentController::class);
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('{payment}/process', [PaymentController::class, 'process'])->name('process');
        Route::post('{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
        Route::get('analytics', [PaymentController::class, 'analytics'])->name('payments-analytics');
        Route::post('{payment}/send-reminder', [PaymentController::class, 'sendReminder'])->name('send-reminder');
    });

    // Stock Management
    // Direct routes for stock pages - placing these BEFORE the resource route to avoid conflicts
    Route::get('stocks/analytics', [StockAnalyticsController::class, 'index'])->name('stocks.analytics');
    Route::get('stocks/low-stock', [StockController::class, 'lowStock'])->name('stocks.low_stock');
    
    // Stock resource and other routes
    Route::resource('stocks', StockController::class);
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::post('{stock}/add', [StockController::class, 'addStock'])->name('add');
        Route::post('{stock}/remove', [StockController::class, 'removeStock'])->name('remove');
    });
    
    // Stock Categories Management
    Route::resource('stock-categories', StockCategoryController::class);
    
    // Stock Orders Management
    Route::resource('stock-orders', StockOrderController::class);
    Route::prefix('stock-orders')->name('stock-orders.')->group(function () {
        Route::post('{order}/approve', [StockOrderController::class, 'approve'])->name('approve');
        Route::post('{order}/deliver', [StockOrderController::class, 'deliver'])->name('deliver');
        Route::post('{order}/cancel', [StockOrderController::class, 'cancel'])->name('cancel');
        Route::post('{order}/update-payment', [StockOrderController::class, 'updatePaymentStatus'])->name('update-payment');
    });
    
    // Suppliers Management
    Route::resource('suppliers', SupplierController::class);
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('{supplier}/orders', [SupplierController::class, 'orders'])->name('orders');
        Route::get('{supplier}/stocks', [SupplierController::class, 'stocks'])->name('stocks');
    });

    // Intern Management
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::post('/students/bulk-delete', [StudentController::class, 'bulkDestroy'])->name('students.bulk-destroy');
    
    Route::resource('criteria', CriteriaController::class);
    Route::get('criteria-weights', [CriteriaController::class, 'weights'])->name('criteria.weights');
    Route::put('criteria-weights', [CriteriaController::class, 'updateWeights'])->name('criteria.updateWeights');
    
    Route::prefix('candidates')->name('candidates.')->group(function () {
        Route::get('/', [CandidateController::class, 'index'])->name('index');
        Route::get('/create', [CandidateController::class, 'create'])->name('create');
        
        // API endpoint to check if candidate is already a trainee
        Route::get('/{candidate}/check-trainee', [CandidateController::class, 'checkIfTrainee'])
            ->name('check-trainee');
        Route::post('/', [CandidateController::class, 'store'])->name('store');
        Route::get('/{candidate}', [CandidateController::class, 'show'])->name('show');
        Route::get('/{candidate}/edit', [CandidateController::class, 'edit'])->name('edit');
        Route::put('/{candidate}', [CandidateController::class, 'update'])->name('update');
        Route::delete('/{candidate}', [CandidateController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-accept', [CandidateController::class, 'bulkAccept'])->name('bulk-accept');
        Route::post('/bulk-destroy', [CandidateController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/accepted/list', [CandidateController::class, 'accepted'])->name('accepted');
        Route::post('/{candidate}/accept', [CandidateController::class, 'accept'])->name('accept');
        Route::post('/{candidate}/convert', [CandidateController::class, 'convertToTrainee'])->name('convert');
        Route::post('/{candidate}/reject', [CandidateController::class, 'reject'])->name('reject');
        Route::delete('/documents/{document}', [CandidateController::class, 'deleteDocument'])->name('delete-document');
        Route::get('/{candidate}/download-documents', [CandidateController::class, 'downloadDocuments'])->name('download-documents');
        Route::post('/bulk-reject', [CandidateController::class, 'bulkReject'])->name('bulk-reject');
    });
    
    // Document download route
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Help Center
    Route::prefix('help-center')->name('help-center.')->group(function () {
        Route::get('/', [HelpCenterController::class, 'index'])->name('index');
        Route::get('search', [HelpCenterController::class, 'search'])->name('search');
        Route::get('category/{slug}', [HelpCenterController::class, 'category'])->name('category');
        Route::get('article/{slug}', [HelpCenterController::class, 'show'])->name('show');
        Route::get('contact', [HelpCenterController::class, 'contact'])->name('contact');
        Route::post('contact', [HelpCenterController::class, 'submitContact'])->name('submit-contact');
    });

    // Profile Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
    });

    // Subject and Enrollment routes removed as they are not needed in this application

    // Grade Management Routes - Removed

    // Room Management Routes
    Route::resource('rooms', RoomController::class);
    Route::post('/rooms/{room}/allocate', [RoomController::class, 'allocate'])->name('rooms.allocate');
    Route::post('/rooms/{room}/deallocate/{allocation}', [RoomController::class, 'deallocate'])->name('rooms.deallocate');
    Route::post('/rooms/{room}/maintenance', [RoomController::class, 'maintenance'])->name('rooms.maintenance');
    Route::get('/available-rooms', [RoomController::class, 'getAvailableRooms'])->name('rooms.available');

    // Academic Terms Management - Removed

    // Settings Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    // Profile Management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Staff Management Routes (Only accessible to admins and directors)
    Route::middleware(['auth'])->group(function () {
        Route::resource('staff', StaffController::class);
    });
    
    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->middleware(['auth'])->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/dashboard', [NotificationController::class, 'dashboard'])->name('dashboard');
        Route::post('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        
        // Manual check routes
        Route::get('/check-stock', [NotificationController::class, 'checkStockLevels'])->name('check-stock');
        Route::get('/check-payments', [NotificationController::class, 'checkPayments'])->name('check-payments');
        Route::get('/check-absences', [NotificationController::class, 'checkAbsences'])->name('check-absences');
    });
    
    // Profile notification routes
    Route::post('/profile/notifications/{id}/read', [ProfileController::class, 'markNotificationAsRead'])->name('profile.notifications.read');
    Route::post('/profile/notifications/read-all', [ProfileController::class, 'markAllNotificationsAsRead'])->name('profile.notifications.readAll');
    Route::delete('/profile/notifications/{id}', [ProfileController::class, 'deleteNotification'])->name('profile.notifications.delete');
    Route::delete('/profile/notifications', [ProfileController::class, 'deleteAllNotifications'])->name('profile.notifications.deleteAll');
});

// Temporary route to check database columns (remove after use)
Route::middleware(['auth'])->get('/temp/check-candidate', function () {
    // Get the first candidate with all fields
    $candidate = \App\Models\Candidate::first();
    
    // Get the table columns
    $columns = \Schema::getColumnListing('candidates');
    
    return response()->json([
        'candidate' => $candidate,
        'columns' => $columns
    ]);
})->name('temp.candidate');

// Temporary route to check database columns (remove after use)
Route::middleware(['auth'])->get('/temp/check-columns', function () {
    $columns = \DB::select('SHOW COLUMNS FROM students');
    return response()->json($columns);
})->name('temp.columns');

// Another way to check columns
Route::get('/check-columns', function () {
    $columns = \Schema::getColumnListing('students');
    return response()->json($columns);
});

// Temporary route to check student data in HTML format
Route::get('/temp/student-info', function () {
    // Get the first student
    $student = \DB::table('students')->first();
    
    if (!$student) {
        return "No students found in the database.";
    }
    
    // Get table columns
    $columns = \Schema::getColumnListing('students');
    
    // Start building HTML
    $html = "<h1>Student Information</h1>";
    $html .= "<h3>Table Structure:</h3>";
    $html .= "<pre>" . print_r(\Schema::getColumnListing('students'), true) . "</pre>";
    
    $html .= "<h3>Student Data:</h3>";
    $html .= "<table border='1' cellpadding='5' cellspacing='0'>";
    $html .= "<tr><th>Field</th><th>Value</th></tr>";
    
    foreach ($student as $field => $value) {
        $html .= "<tr>";
        $html .= "<td><strong>" . e($field) . "</strong></td>";
        $html .= "<td>" . e($value) . "</td>";
    $html .= "</tr>";
    }
    
    $html .= "</table>";
    
    // Check if academic_year and nationality are in fillable
    $studentModel = new \App\Models\Student();
    $html .= "<h3>Fillable Fields:</h3>";
    $html .= "<pre>" . print_r($studentModel->getFillable(), true) . "</pre>";
    
    return $html;
});

// API Routes for Dynamic Data
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('expenses-stats', [StockStatisticsController::class, 'getExpenseStats'])->name('expenses.stats');
    Route::get('notifications/recent', [NotificationController::class, 'getRecentNotifications'])->name('notifications.recent');
});

// Error Pages
Route::get('/test/error/{code}', [ErrorController::class, 'show'])->name('test.error');

Route::post('/candidates/bulk-convert', [App\Http\Controllers\CandidateController::class, 'bulkConvert'])->name('candidates.bulk-convert');
