<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\Room;
use App\Models\Grade;
use App\Models\SubjectEnrollment;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with key statistics and recent activities.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get current academic term
        $currentTerm = AcademicTerm::current()->first();

        // Gather statistics
        $stats = [
            'total_students' => User::whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->count(),
            'active_subjects' => Subject::active()->count(),
            'available_rooms' => Room::available()->count(),
            'active_staff' => User::whereHas('roles', function($q) {
                $q->whereIn('name', ['teacher', 'admin', 'staff']);
            })->count()
        ];

        // Get recent activities
        $recentActivities = collect();

        // Recent enrollments
        $recentEnrollments = SubjectEnrollment::with(['student', 'subject'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($enrollment) {
                return [
                    'type' => 'enrollment',
                    'description' => "New enrollment in {$enrollment->subject->name}",
                    'user' => $enrollment->student->name,
                    'time' => $enrollment->created_at,
                    'status' => $enrollment->status
                ];
            });
        $recentActivities = $recentActivities->concat($recentEnrollments);

        // Recent grades
        $recentGrades = Grade::with(['student', 'subject'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($grade) {
                return [
                    'type' => 'grade',
                    'description' => "Grade submitted for {$grade->subject->name}",
                    'user' => $grade->student->name,
                    'time' => $grade->created_at,
                    'status' => $grade->isPassingGrade() ? 'passed' : 'failed'
                ];
            });
        $recentActivities = $recentActivities->concat($recentGrades);

        // Sort activities by time
        $recentActivities = $recentActivities->sortByDesc('time')->take(5);

        // Get performance metrics for current term
        $performanceMetrics = [];
        if ($currentTerm) {
            $studentCount = User::whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->count();

            $performanceMetrics = [
                'average_grade' => Grade::where('academic_term_id', $currentTerm->id)
                    ->avg('score'),
                'passing_rate' => Grade::where('academic_term_id', $currentTerm->id)
                    ->where('score', '>=', 60)
                    ->count() / max(1, Grade::where('academic_term_id', $currentTerm->id)
                    ->count()) * 100,
                'enrollment_rate' => SubjectEnrollment::where('academic_term_id', $currentTerm->id)
                    ->count() / max(1, $studentCount) * 100
            ];
        }

        // Get system notifications
        $notifications = [
            [
                'type' => 'success',
                'title' => 'Update Successful',
                'message' => 'The system has been successfully updated to the latest version.'
            ],
            [
                'type' => 'warning',
                'title' => 'Maintenance Scheduled',
                'message' => 'System maintenance is scheduled for tomorrow at 2:00 AM.'
            ],
            [
                'type' => 'error',
                'title' => 'Storage Warning',
                'message' => 'Storage space is running low. Please clean up unnecessary files.'
            ]
        ];

        return view('dashboard.index', compact(
            'stats',
            'recentActivities',
            'performanceMetrics',
            'notifications',
            'currentTerm'
        ));
    }
}
