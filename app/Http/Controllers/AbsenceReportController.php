<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\AbsenceType;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenceReportController extends Controller
{
    /**
     * Display a listing of the reports available.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Summary statistics for the dashboard
        $totalAbsences = Absence::count();
        $pendingAbsences = Absence::where('status', 'pending')->count();
        $approvedAbsences = Absence::where('status', 'approved')->count();
        $rejectedAbsences = Absence::where('status', 'rejected')->count();
        
        // Get absence types with counts
        $absenceTypeStats = AbsenceType::withCount('absences')
            ->orderBy('absences_count', 'desc')
            ->get();
        
        // Get recent absences for quick view
        $recentAbsences = Absence::with(['student', 'absenceType'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('absence-reports.index', compact(
            'totalAbsences', 
            'pendingAbsences', 
            'approvedAbsences', 
            'rejectedAbsences',
            'absenceTypeStats',
            'recentAbsences'
        ));
    }

    /**
     * Display monthly absence report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function monthly(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        // Get monthly absence counts
        $monthlyData = Absence::select(
                DB::raw('MONTH(start_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('start_date', $year)
            ->groupBy(DB::raw('MONTH(start_date)'))
            ->orderBy(DB::raw('MONTH(start_date)'))
            ->get()
            ->keyBy('month');
            
        // Format data for chart
        $chartData = [];
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        foreach ($monthNames as $monthNum => $monthName) {
            $chartData[] = [
                'month' => $monthName,
                'total' => $monthlyData[$monthNum]->total ?? 0
            ];
        }
        
        return view('absence-reports.monthly', compact('chartData', 'year'));
    }

    /**
     * Display absence report by type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function byType(Request $request)
    {
        $period = $request->input('period', 'year');
        $startDate = null;
        $endDate = null;
        
        // Determine date range based on period
        switch ($period) {
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'quarter':
                $startDate = now()->startOfQuarter();
                $endDate = now()->endOfQuarter();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            default:
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
        }
        
        // Get absence counts by type
        $absencesByType = AbsenceType::withCount(['absences' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate]);
            }])
            ->orderBy('absences_count', 'desc')
            ->get();
            
        return view('absence-reports.by-type', compact('absencesByType', 'period', 'startDate', 'endDate'));
    }

    /**
     * Display absence report by student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function byStudent(Request $request)
    {
        $searchTerm = $request->input('search', '');
        
        // Get students with absence counts
        $studentsQuery = Student::withCount('absences')
            ->orderBy('absences_count', 'desc');
            
        // Apply search filter if provided
        if (!empty($searchTerm)) {
            $studentsQuery->where(function($query) use ($searchTerm) {
                $query->where('first_name', 'like', "%{$searchTerm}%")
                    ->orWhere('last_name', 'like', "%{$searchTerm}%")
                    ->orWhere('student_id', 'like', "%{$searchTerm}%");
            });
        }
        
        $students = $studentsQuery->paginate(15);
        
        return view('absence-reports.by-student', compact('students', 'searchTerm'));
    }
}
