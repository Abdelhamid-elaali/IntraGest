<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsencesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockStatisticsController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CriteriaController;

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

    // Absence Management
    Route::resource('absences', AbsencesController::class);
    Route::prefix('absences')->name('absences.')->group(function () {
        Route::get('reports', [AbsencesController::class, 'reports'])->name('reports');
        Route::post('{absence}/approve', [AbsencesController::class, 'approve'])->name('approve');
        Route::post('{absence}/reject', [AbsencesController::class, 'reject'])->name('reject');
    });

    // Payment Management
    Route::resource('payments', PaymentController::class);
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('{payment}/process', [PaymentController::class, 'process'])->name('process');
        Route::post('{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
        Route::get('analytics', [PaymentController::class, 'analytics'])->name('analytics');
        Route::post('{payment}/send-reminder', [PaymentController::class, 'sendReminder'])->name('send-reminder');
    });

    // Stock Management
    Route::resource('stocks', StockController::class);
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::post('{stock}/add', [StockController::class, 'addStock'])->name('add');
        Route::post('{stock}/remove', [StockController::class, 'removeStock'])->name('remove');
        Route::get('analytics', [StockController::class, 'analytics'])->name('analytics');
        Route::get('low-stock', [StockController::class, 'lowStock'])->name('low-stock');
    });

    // Intern Management
    Route::resource('students', StudentController::class);
    
    Route::resource('criteria', CriteriaController::class);
    Route::get('criteria-weights', [CriteriaController::class, 'weights'])->name('criteria.weights');
    Route::put('criteria-weights', [CriteriaController::class, 'updateWeights'])->name('criteria.updateWeights');
    
    Route::prefix('candidates')->name('candidates.')->group(function () {
        Route::get('/', [CandidateController::class, 'index'])->name('index');
        Route::get('/create', [CandidateController::class, 'create'])->name('create');
        Route::post('/', [CandidateController::class, 'store'])->name('store');
        Route::get('/{candidate}/edit', [CandidateController::class, 'edit'])->name('edit');
        Route::put('/{candidate}', [CandidateController::class, 'update'])->name('update');
        Route::delete('/{candidate}', [CandidateController::class, 'destroy'])->name('destroy');
        Route::get('/accepted', [CandidateController::class, 'accepted'])->name('accepted');
    });

    // Help Center
    Route::prefix('help-center')->name('help-center.')->group(function () {
        Route::get('/', [HelpCenterController::class, 'index'])->name('index');
        Route::get('search', [HelpCenterController::class, 'search'])->name('search');
        Route::get('category/{slug}', [HelpCenterController::class, 'category'])->name('category');
        Route::get('article/{slug}', [HelpCenterController::class, 'show'])->name('show');
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

    // Subjects Management
    Route::resource('subjects', SubjectController::class);
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::post('{subject}/activate', [SubjectController::class, 'activate'])->name('activate');
        Route::post('{subject}/deactivate', [SubjectController::class, 'deactivate'])->name('deactivate');
        Route::get('{subject}/students', [SubjectController::class, 'students'])->name('students');
        Route::get('{subject}/grades', [SubjectController::class, 'grades'])->name('grades');
        Route::get('{subject}/analytics', [SubjectController::class, 'analytics'])->name('analytics');
    });

    // Enrollments Management
    Route::resource('enrollments', EnrollmentController::class);
    Route::prefix('enrollments')->name('enrollments.')->group(function () {
        Route::post('{enrollment}/approve', [EnrollmentController::class, 'approve'])->name('approve');
        Route::post('{enrollment}/reject', [EnrollmentController::class, 'reject'])->name('reject');
        Route::post('{enrollment}/drop', [EnrollmentController::class, 'drop'])->name('drop');
        Route::get('student/{student}', [EnrollmentController::class, 'studentEnrollments'])->name('student');
        Route::get('term/{term}', [EnrollmentController::class, 'termEnrollments'])->name('term');
    });

    // Grade Management Routes
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/', [GradeController::class, 'index'])->name('index');
        Route::get('/create', [GradeController::class, 'create'])->name('create');
        Route::post('/', [GradeController::class, 'store'])->name('store');
        Route::get('/{grade}', [GradeController::class, 'show'])->name('show');
        Route::get('/{grade}/edit', [GradeController::class, 'edit'])->name('edit');
        Route::put('/{grade}', [GradeController::class, 'update'])->name('update');
        Route::delete('/{grade}', [GradeController::class, 'destroy'])->name('destroy');
        
        Route::get('/student/{student}', [GradeController::class, 'showStudent'])->name('student');
        Route::get('/subject/{subject}', [GradeController::class, 'showSubject'])->name('subject');
        Route::get('/analytics', [GradeController::class, 'analytics'])->name('analytics');
    });

    // Room Management Routes
    Route::resource('rooms', RoomController::class);
    Route::post('/rooms/{room}/allocate', [RoomController::class, 'allocate'])->name('rooms.allocate');
    Route::post('/rooms/{room}/deallocate/{allocation}', [RoomController::class, 'deallocate'])->name('rooms.deallocate');
    Route::post('/rooms/{room}/maintenance', [RoomController::class, 'maintenance'])->name('rooms.maintenance');
    Route::get('/available-rooms', [RoomController::class, 'getAvailableRooms'])->name('rooms.available');

    // Academic Terms Management
    Route::resource('terms', AcademicTermController::class);
    Route::post('/terms/{term}/set-current', [AcademicTermController::class, 'setCurrent'])->name('terms.setCurrent');
    Route::get('/terms/{term}/subjects', [AcademicTermController::class, 'getSubjects'])->name('terms.subjects');
    Route::get('/terms/{term}/enrollments', [AcademicTermController::class, 'getEnrollments'])->name('terms.enrollments');
    Route::get('/terms/{term}/grades', [AcademicTermController::class, 'getGrades'])->name('terms.grades');
    Route::get('/terms/{term}/analytics', [AcademicTermController::class, 'analytics'])->name('terms.analytics');

    // Settings Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    // Profile Management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Notification Routes
    Route::post('/profile/notifications/{id}/read', [ProfileController::class, 'markNotificationAsRead'])->name('profile.notifications.read');
    Route::post('/profile/notifications/read-all', [ProfileController::class, 'markAllNotificationsAsRead'])->name('profile.notifications.readAll');
    Route::delete('/profile/notifications/{id}', [ProfileController::class, 'deleteNotification'])->name('profile.notifications.delete');
    Route::delete('/profile/notifications', [ProfileController::class, 'deleteAllNotifications'])->name('profile.notifications.deleteAll');
});

// API Routes for Dynamic Data
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('expenses-stats', [StockStatisticsController::class, 'getExpenseStats'])->name('expenses.stats');
});
