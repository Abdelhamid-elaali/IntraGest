<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AbsenceApproved;
use App\Notifications\AbsenceRejected;
use App\Notifications\RepeatedAbsenceAlert;
use PDF;

class AbsencesController extends Controller
{
    public function index(Request $request)
    {
        $query = Absence::query()->with(['student.user']);

        // Apply filters if provided
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Get the absences with pagination
        $absences = $query->latest()->paginate(10)->withQueryString();
        
        // Get all students for the filter dropdown
        $students = Student::all();
        
        return view('absences.index', compact('absences', 'students'));
    }

    public function create()
    {
        $students = Student::all();
        return view('absences.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:excused,unexcused,late,medical,family',
            'duration' => 'required_if:type,late|nullable|integer|min:1',
            'reason' => 'required|string|max:1000',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Handle file uploads
        $documents = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $path = $file->store('absences/documents', 'public');
                $documents[] = $path;
            }
        }

        // Create the absence record
        $absence = new Absence();
        $absence->student_id = $validated['student_id'];
        $absence->start_date = $validated['start_date'];
        $absence->end_date = $validated['end_date'];
        $absence->type = $validated['type'];
        $absence->reason = $validated['reason'];
        $absence->status = 'pending';
        $absence->supporting_documents = $documents;
        
        if ($validated['type'] === 'late' && isset($validated['duration'])) {
            $absence->duration = $validated['duration'];
        }
        
        $absence->save();

        // Check for repeated absences and send alerts if necessary
        $this->checkForRepeatedAbsences($absence);

        return redirect()->route('absences.index')
            ->with('success', 'Absence record created successfully.');
    }

    public function show(Absence $absence)
    {
        return view('absences.show', compact('absence'));
    }
    
    /**
     * Approve an absence request
     */
    public function approve(Absence $absence)
    {
        $absence->status = 'approved';
        $absence->approved_at = now();
        $absence->approver_id = Auth::id();
        $absence->save();
        
        // Send notification to the student
        if ($absence->student && $absence->student->user) {
            $absence->student->user->notify(new AbsenceApproved($absence));
        }
        
        return redirect()->route('absences.show', $absence)
            ->with('success', 'Absence request has been approved.');
    }
    
    /**
     * Reject an absence request
     */
    public function reject(Request $request, Absence $absence)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);
        
        $absence->status = 'rejected';
        $absence->approved_at = now();
        $absence->approver_id = Auth::id();
        $absence->notes = $validated['reason'];
        $absence->save();
        
        // Send notification to the student
        if ($absence->student && $absence->student->user) {
            $absence->student->user->notify(new AbsenceRejected($absence));
        }
        
        return redirect()->route('absences.show', $absence)
            ->with('success', 'Absence request has been rejected.');
    }

    public function edit(Absence $absence)
    {
        $students = Student::all();
        return view('absences.edit', compact('absence', 'students'));
    }

    public function update(Request $request, Absence $absence)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:excused,unexcused,late,medical,family',
            'duration' => 'required_if:type,late|nullable|integer|min:1',
            'reason' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string|max:1000',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'documents_to_remove' => 'nullable|string',
        ]);

        // Handle document removal if requested
        if (!empty($validated['documents_to_remove'])) {
            $documentsToRemove = json_decode($validated['documents_to_remove'], true);
            $currentDocuments = $absence->supporting_documents ?? [];
            $updatedDocuments = [];
            
            foreach ($currentDocuments as $document) {
                if (!in_array($document, $documentsToRemove)) {
                    $updatedDocuments[] = $document;
                } else {
                    // Delete the file from storage
                    Storage::disk('public')->delete($document);
                }
            }
            
            $absence->supporting_documents = $updatedDocuments;
        }

        // Handle new file uploads
        if ($request->hasFile('supporting_documents')) {
            $documents = $absence->supporting_documents ?? [];
            foreach ($request->file('supporting_documents') as $file) {
                $path = $file->store('absences/documents', 'public');
                $documents[] = $path;
            }
            $absence->supporting_documents = $documents;
        }

        // Update other fields
        $absence->student_id = $validated['student_id'];
        $absence->start_date = $validated['start_date'];
        $absence->end_date = $validated['end_date'];
        $absence->type = $validated['type'];
        $absence->reason = $validated['reason'];
        $absence->status = $validated['status'];
        
        if (isset($validated['notes'])) {
            $absence->notes = $validated['notes'];
        }
        
        if ($validated['type'] === 'late' && isset($validated['duration'])) {
            $absence->duration = $validated['duration'];
        } else {
            $absence->duration = null;
        }
        
        // If status changed to approved or rejected, set approval timestamp
        if (($validated['status'] === 'approved' || $validated['status'] === 'rejected') && $absence->getOriginal('status') === 'pending') {
            $absence->approved_at = now();
            $absence->approver_id = Auth::id();
            
            // Send notification based on status
            if ($absence->student && $absence->student->user) {
                if ($validated['status'] === 'approved') {
                    $absence->student->user->notify(new AbsenceApproved($absence));
                } else {
                    $absence->student->user->notify(new AbsenceRejected($absence));
                }
            }
        }
        
        $absence->save();

        return redirect()->route('absences.show', $absence)
            ->with('success', 'Absence record updated successfully.');
    }

    public function destroy(Absence $absence)
    {
        // Delete associated documents from storage
        if (!empty($absence->supporting_documents)) {
            foreach ($absence->supporting_documents as $document) {
                Storage::disk('public')->delete($document);
            }
        }
        
        $absence->delete();

        return redirect()->route('absences.index')
            ->with('success', 'Absence record deleted successfully.');
    }
    
    /**
     * Display absence reports and analytics
     */
    public function reports(Request $request)
    {
        // Get all students for filter dropdown
        $students = Student::all();
        
        // Base query for absences
        $query = Absence::query();
        
        // Apply filters if provided
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }
        
        // Summary statistics
        $totalAbsences = $query->count();
        $approvedAbsences = (clone $query)->where('status', 'approved')->count();
        $pendingAbsences = (clone $query)->where('status', 'pending')->count();
        $rejectedAbsences = (clone $query)->where('status', 'rejected')->count();
        
        // Absence types breakdown
        $excusedAbsences = (clone $query)->where('type', 'excused')->count();
        $unexcusedAbsences = (clone $query)->where('type', 'unexcused')->count();
        $lateAbsences = (clone $query)->where('type', 'late')->count();
        $medicalAbsences = (clone $query)->where('type', 'medical')->count();
        $familyAbsences = (clone $query)->where('type', 'family')->count();
        
        // Trend data (last 6 months)
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $trendData = [];
        $trendLabels = [];
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $count = (clone $query)
                ->where('start_date', '>=', $monthStart)
                ->where('start_date', '<=', $monthEnd)
                ->count();
            
            $trendData[] = $count;
            $trendLabels[] = $date->format('M Y');
        }
        
        // Students with most absences
        $topAbsentStudents = Student::withCount(['absences' => function ($query) use ($request) {
                if ($request->filled('start_date')) {
                    $query->where('start_date', '>=', $request->start_date);
                }
                
                if ($request->filled('end_date')) {
                    $query->where('end_date', '<=', $request->end_date);
                }
            }])
            ->withCount(['absences as excused_count' => function ($query) use ($request) {
                $query->where('type', 'excused');
                if ($request->filled('start_date')) {
                    $query->where('start_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->where('end_date', '<=', $request->end_date);
                }
            }])
            ->withCount(['absences as unexcused_count' => function ($query) use ($request) {
                $query->where('type', 'unexcused');
                if ($request->filled('start_date')) {
                    $query->where('start_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->where('end_date', '<=', $request->end_date);
                }
            }])
            ->withCount(['absences as late_count' => function ($query) use ($request) {
                $query->where('type', 'late');
                if ($request->filled('start_date')) {
                    $query->where('start_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->where('end_date', '<=', $request->end_date);
                }
            }])
            ->with('user')
            ->orderByDesc('absences_count')
            ->limit(10)
            ->get();
        
        // Calculate total days absent for each student
        foreach ($topAbsentStudents as $student) {
            $totalDays = 0;
            $absences = $student->absences;
            
            foreach ($absences as $absence) {
                if ($absence->type !== 'late') {
                    $totalDays += $absence->getDurationInDays();
                }
            }
            
            $student->total_days = $totalDays;
        }
        
        // Recent absences
        $recentAbsences = Absence::with('student.user')
            ->latest('start_date')
            ->limit(10)
            ->get();
        
        // Check if export is requested
        if ($request->has('export') && $request->export === 'pdf') {
            return $this->exportAbsenceReport($query->get());
        }
        
        return view('absences.reports', compact(
            'students', 'totalAbsences', 'approvedAbsences', 'pendingAbsences', 'rejectedAbsences',
            'excusedAbsences', 'unexcusedAbsences', 'lateAbsences', 'medicalAbsences', 'familyAbsences',
            'trendData', 'trendLabels', 'topAbsentStudents', 'recentAbsences'
        ));
    }
    
    /**
     * Export absence report as PDF
     */
    private function exportAbsenceReport($absences)
    {
        $data = [
            'absences' => $absences,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'generated_by' => Auth::user()->name,
        ];
        
        $pdf = PDF::loadView('absences.pdf_report', $data);
        return $pdf->download('absence_report_' . now()->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Check for repeated absences and send alerts if necessary
     */
    private function checkForRepeatedAbsences(Absence $absence)
    {
        // Get the student's absences in the last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $recentAbsences = Absence::where('student_id', $absence->student_id)
            ->where('start_date', '>=', $thirtyDaysAgo)
            ->count();
        
        // If this is the third or more absence in 30 days, send an alert
        if ($recentAbsences >= 3) {
            // Get admin users to notify
            $admins = User::role('admin')->get();
            
            // Send notification to all admins
            Notification::send($admins, new RepeatedAbsenceAlert($absence->student, $recentAbsences));
        }
    }
}
