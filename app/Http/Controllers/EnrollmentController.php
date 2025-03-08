<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use App\Models\Grade;
use App\Models\SubjectEnrollment;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubjectEnrollment::query();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('term_id')) {
            $query->where('academic_term_id', $request->term_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Role-based filtering
        if (Auth::user()->hasRole('student')) {
            $query->where('student_id', Auth::id());
        } elseif (Auth::user()->hasRole('teacher')) {
            $query->whereHas('subject', function ($q) {
                $q->whereHas('teachers', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            });
        }

        $enrollments = $query->with(['student', 'subject', 'academicTerm'])
            ->latest()
            ->paginate(20);

        $terms = AcademicTerm::orderBy('start_date', 'desc')->get();
        $subjects = Subject::active()->orderBy('name')->get();

        return view('enrollments.index', compact('enrollments', 'terms', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', SubjectEnrollment::class);

        $currentTerm = AcademicTerm::current()->firstOrFail();
        $subjects = Subject::active()->orderBy('name')->get();
        $students = User::role('student')->orderBy('name')->get();

        return view('enrollments.create', compact('currentTerm', 'subjects', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', SubjectEnrollment::class);

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'notes' => 'nullable|string'
        ]);

        // Check if student is already enrolled
        $existingEnrollment = SubjectEnrollment::where([
            'student_id' => $validated['student_id'],
            'subject_id' => $validated['subject_id'],
            'academic_term_id' => $validated['academic_term_id']
        ])->exists();

        if ($existingEnrollment) {
            return back()->with('error', 'Student is already enrolled in this subject.');
        }

        // Check prerequisites
        $subject = Subject::findOrFail($validated['subject_id']);
        $prerequisitesMet = $subject->prerequisites()->get()->every(function ($prerequisite) use ($validated) {
            return Grade::where([
                'student_id' => $validated['student_id'],
                'subject_id' => $prerequisite->id
            ])->where('score', '>=', $prerequisite->passing_grade)->exists();
        });

        if (!$prerequisitesMet) {
            return back()->with('error', 'Student does not meet the prerequisites for this subject.');
        }

        DB::beginTransaction();
        try {
            $enrollment = SubjectEnrollment::create([
                'student_id' => $validated['student_id'],
                'subject_id' => $validated['subject_id'],
                'academic_term_id' => $validated['academic_term_id'],
                'status' => 'pending',
                'notes' => $validated['notes']
            ]);

            DB::commit();
            return redirect()->route('enrollments.show', $enrollment)
                ->with('success', 'Enrollment created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create enrollment. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubjectEnrollment $enrollment)
    {
        $this->authorize('view', $enrollment);

        $enrollment->load(['student', 'subject', 'academicTerm', 'grades']);
        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubjectEnrollment $enrollment)
    {
        $this->authorize('update', $enrollment);

        return view('enrollments.edit', compact('enrollment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubjectEnrollment $enrollment)
    {
        $this->authorize('update', $enrollment);

        $validated = $request->validate([
            'notes' => 'nullable|string'
        ]);

        $enrollment->update($validated);
        return redirect()->route('enrollments.show', $enrollment)
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubjectEnrollment $enrollment)
    {
        $this->authorize('delete', $enrollment);

        if ($enrollment->status !== 'pending') {
            return back()->with('error', 'Only pending enrollments can be deleted.');
        }

        $enrollment->delete();
        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }

    /**
     * Approve an enrollment.
     */
    public function approve(SubjectEnrollment $enrollment)
    {
        $this->authorize('approve', $enrollment);

        if ($enrollment->status !== 'pending') {
            return back()->with('error', 'Only pending enrollments can be approved.');
        }

        $enrollment->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        return back()->with('success', 'Enrollment approved successfully.');
    }

    /**
     * Reject an enrollment.
     */
    public function reject(SubjectEnrollment $enrollment)
    {
        $this->authorize('reject', $enrollment);

        if ($enrollment->status !== 'pending') {
            return back()->with('error', 'Only pending enrollments can be rejected.');
        }

        $enrollment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id()
        ]);

        return back()->with('success', 'Enrollment rejected successfully.');
    }

    /**
     * Drop an enrollment.
     */
    public function drop(Request $request, SubjectEnrollment $enrollment)
    {
        $this->authorize('drop', $enrollment);

        if ($enrollment->status !== 'active') {
            return back()->with('error', 'Only active enrollments can be dropped.');
        }

        $validated = $request->validate([
            'reason' => 'required|string'
        ]);

        $enrollment->update([
            'status' => 'dropped',
            'dropped_at' => now(),
            'drop_reason' => $validated['reason']
        ]);

        return back()->with('success', 'Enrollment dropped successfully.');
    }

    /**
     * Display enrollments for a specific student.
     */
    public function studentEnrollments(User $student)
    {
        $this->authorize('viewAny', SubjectEnrollment::class);

        $enrollments = SubjectEnrollment::where('student_id', $student->id)
            ->with(['subject', 'academicTerm'])
            ->latest()
            ->paginate(20);

        return view('enrollments.student', compact('student', 'enrollments'));
    }

    /**
     * Display enrollments for a specific term.
     */
    public function termEnrollments(AcademicTerm $term)
    {
        $this->authorize('viewAny', SubjectEnrollment::class);

        $enrollments = SubjectEnrollment::where('academic_term_id', $term->id)
            ->with(['student', 'subject'])
            ->latest()
            ->paginate(20);

        return view('enrollments.term', compact('term', 'enrollments'));
    }

    /**
     * Validate subject selection for enrollment (API endpoint).
     */
    public function validateSelection(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);
        $student = User::findOrFail($validated['student_id']);

        $prerequisites = $subject->prerequisites;
        $missingPrerequisites = [];

        foreach ($prerequisites as $prerequisite) {
            $passed = Grade::where([
                'student_id' => $student->id,
                'subject_id' => $prerequisite->id
            ])->where('score', '>=', $prerequisite->passing_grade)->exists();

            if (!$passed) {
                $missingPrerequisites[] = $prerequisite->name;
            }
        }

        return response()->json([
            'valid' => empty($missingPrerequisites),
            'missing_prerequisites' => $missingPrerequisites
        ]);
    }
}
