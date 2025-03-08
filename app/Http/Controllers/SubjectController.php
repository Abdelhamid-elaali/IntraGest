<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use App\Models\Grade;
use App\Models\SubjectEnrollment;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }

        $subjects = $query->withCount(['students', 'prerequisites'])
            ->latest()
            ->paginate(10);

        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Subject::distinct('department')->pluck('department');
        $availablePrerequisites = Subject::active()->get();
        
        return view('subjects.create', compact('departments', 'availablePrerequisites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'description' => 'required|string',
            'department' => 'required|string|max:100',
            'credits' => 'required|integer|min:1',
            'level' => 'required|integer|min:1',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:subjects,id',
            'passing_grade' => 'required|integer|min:0|max:100',
            'syllabus' => 'nullable|file|mimes:pdf|max:10240',
            'assessment_criteria' => 'required|json'
        ]);

        DB::beginTransaction();
        try {
            $subject = Subject::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'department' => $validated['department'],
                'credits' => $validated['credits'],
                'level' => $validated['level'],
                'passing_grade' => $validated['passing_grade'],
                'assessment_criteria' => $validated['assessment_criteria'],
                'active' => true
            ]);

            if ($request->hasFile('syllabus')) {
                $path = $request->file('syllabus')->store('syllabi', 'public');
                $subject->update(['syllabus_path' => $path]);
            }

            if (!empty($validated['prerequisites'])) {
                $subject->prerequisites()->attach($validated['prerequisites']);
            }

            DB::commit();
            return redirect()->route('subjects.show', $subject)
                ->with('success', 'Subject created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create subject. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load(['prerequisites', 'students'])
            ->loadCount(['students', 'prerequisites']);

        $currentTerm = AcademicTerm::current()->first();
        $enrollments = [];
        $grades = [];

        if ($currentTerm) {
            $enrollments = SubjectEnrollment::where('subject_id', $subject->id)
                ->where('academic_term_id', $currentTerm->id)
                ->with('student')
                ->get();

            $grades = Grade::where('subject_id', $subject->id)
                ->where('academic_term_id', $currentTerm->id)
                ->with('student')
                ->get();
        }

        return view('subjects.show', compact('subject', 'enrollments', 'grades', 'currentTerm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $departments = Subject::distinct('department')->pluck('department');
        $availablePrerequisites = Subject::active()
            ->where('id', '!=', $subject->id)
            ->get();

        return view('subjects.edit', compact('subject', 'departments', 'availablePrerequisites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects')->ignore($subject)],
            'description' => 'required|string',
            'department' => 'required|string|max:100',
            'credits' => 'required|integer|min:1',
            'level' => 'required|integer|min:1',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => ['exists:subjects,id', Rule::notIn([$subject->id])],
            'passing_grade' => 'required|integer|min:0|max:100',
            'syllabus' => 'nullable|file|mimes:pdf|max:10240',
            'assessment_criteria' => 'required|json'
        ]);

        DB::beginTransaction();
        try {
            $subject->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'department' => $validated['department'],
                'credits' => $validated['credits'],
                'level' => $validated['level'],
                'passing_grade' => $validated['passing_grade'],
                'assessment_criteria' => $validated['assessment_criteria']
            ]);

            if ($request->hasFile('syllabus')) {
                $path = $request->file('syllabus')->store('syllabi', 'public');
                $subject->update(['syllabus_path' => $path]);
            }

            $subject->prerequisites()->sync($validated['prerequisites'] ?? []);

            DB::commit();
            return redirect()->route('subjects.show', $subject)
                ->with('success', 'Subject updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update subject. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        if ($subject->students()->exists()) {
            return back()->with('error', 'Cannot delete subject with enrolled students.');
        }

        DB::beginTransaction();
        try {
            $subject->prerequisites()->detach();
            $subject->delete();

            DB::commit();
            return redirect()->route('subjects.index')
                ->with('success', 'Subject deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete subject. Please try again.');
        }
    }

    /**
     * Activate a subject.
     */
    public function activate(Subject $subject)
    {
        $subject->update(['active' => true]);
        return back()->with('success', 'Subject activated successfully.');
    }

    /**
     * Deactivate a subject.
     */
    public function deactivate(Subject $subject)
    {
        if ($subject->students()->exists()) {
            return back()->with('error', 'Cannot deactivate subject with enrolled students.');
        }

        $subject->update(['active' => false]);
        return back()->with('success', 'Subject deactivated successfully.');
    }

    /**
     * Display students enrolled in the subject.
     */
    public function students(Subject $subject)
    {
        $currentTerm = AcademicTerm::current()->first();
        $enrollments = SubjectEnrollment::where('subject_id', $subject->id)
            ->when($currentTerm, function ($query) use ($currentTerm) {
                return $query->where('academic_term_id', $currentTerm->id);
            })
            ->with(['student', 'grades'])
            ->paginate(20);

        return view('subjects.students', compact('subject', 'enrollments', 'currentTerm'));
    }

    /**
     * Display grades for the subject.
     */
    public function grades(Subject $subject)
    {
        $currentTerm = AcademicTerm::current()->first();
        $grades = Grade::where('subject_id', $subject->id)
            ->when($currentTerm, function ($query) use ($currentTerm) {
                return $query->where('academic_term_id', $currentTerm->id);
            })
            ->with(['student'])
            ->paginate(20);

        return view('subjects.grades', compact('subject', 'grades', 'currentTerm'));
    }

    /**
     * Display analytics for the subject.
     */
    public function analytics(Subject $subject)
    {
        $currentTerm = AcademicTerm::current()->first();
        
        $metrics = [
            'total_enrollments' => $subject->students()->count(),
            'average_grade' => $subject->grades()->avg('score'),
            'passing_rate' => $subject->grades()
                ->where('score', '>=', $subject->passing_grade)
                ->count() / max(1, $subject->grades()->count()) * 100,
            'prerequisite_subjects' => $subject->prerequisites()->count(),
            'dependent_subjects' => Subject::whereHas('prerequisites', function ($query) use ($subject) {
                $query->where('prerequisite_id', $subject->id);
            })->count()
        ];

        $gradeDistribution = $subject->grades()
            ->when($currentTerm, function ($query) use ($currentTerm) {
                return $query->where('academic_term_id', $currentTerm->id);
            })
            ->select(DB::raw('FLOOR(score/10)*10 as range'), DB::raw('count(*) as count'))
            ->groupBy('range')
            ->orderBy('range')
            ->get();

        return view('subjects.analytics', compact(
            'subject',
            'metrics',
            'gradeDistribution',
            'currentTerm'
        ));
    }

    /**
     * Search subjects (API endpoint).
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $subjects = Subject::where('active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'code']);

        return response()->json($subjects);
    }

    /**
     * Get prerequisites for a subject (API endpoint).
     */
    public function prerequisites(Subject $subject)
    {
        $prerequisites = $subject->prerequisites()
            ->with(['grades' => function ($query) {
                $query->where('student_id', auth()->id());
            }])
            ->get();

        return response()->json($prerequisites);
    }
}
