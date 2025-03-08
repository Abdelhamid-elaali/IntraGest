<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use Illuminate\Http\Request;

class AcademicTermController extends Controller
{
    public function index()
    {
        $terms = AcademicTerm::latest()
            ->withCount(['subjects', 'grades', 'enrollments'])
            ->paginate(15);
            
        return view('terms.index', compact('terms'));
    }

    public function create()
    {
        return view('terms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:semester,trimester,quarter',
            'academic_year' => 'required|string|max:9',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:end_date',
            'drop_deadline' => 'required|date|before:end_date|after:start_date',
            'grading_deadline' => 'required|date|after:end_date',
            'is_current' => 'boolean'
        ]);

        if ($validated['is_current'] ?? false) {
            AcademicTerm::where('is_current', true)
                ->update(['is_current' => false]);
        }

        $term = AcademicTerm::create($validated);

        return redirect()->route('terms.show', $term)
            ->with('success', 'Academic term created successfully.');
    }

    public function show(AcademicTerm $term)
    {
        $term->load(['subjects', 'enrollments.student', 'grades.student']);
        
        $stats = [
            'total_subjects' => $term->subjects()->count(),
            'total_students' => $term->enrollments()->distinct('student_id')->count(),
            'total_grades' => $term->grades()->count(),
            'average_grade' => $term->grades()->avg('score'),
            'pass_rate' => $term->grades()
                ->where('score', '>=', 60)
                ->count() / max($term->grades()->count(), 1) * 100
        ];

        return view('terms.show', compact('term', 'stats'));
    }

    public function edit(AcademicTerm $term)
    {
        return view('terms.edit', compact('term'));
    }

    public function update(Request $request, AcademicTerm $term)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:semester,trimester,quarter',
            'academic_year' => 'required|string|max:9',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:end_date',
            'drop_deadline' => 'required|date|before:end_date|after:start_date',
            'grading_deadline' => 'required|date|after:end_date',
            'is_current' => 'boolean'
        ]);

        if (($validated['is_current'] ?? false) && !$term->is_current) {
            AcademicTerm::where('is_current', true)
                ->update(['is_current' => false]);
        }

        $term->update($validated);

        return redirect()->route('terms.show', $term)
            ->with('success', 'Academic term updated successfully.');
    }

    public function destroy(AcademicTerm $term)
    {
        if ($term->grades()->exists() || $term->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete term with associated grades or enrollments.');
        }

        $term->delete();

        return redirect()->route('terms.index')
            ->with('success', 'Academic term deleted successfully.');
    }

    public function setCurrent(AcademicTerm $term)
    {
        AcademicTerm::where('is_current', true)
            ->update(['is_current' => false]);

        $term->update(['is_current' => true]);

        return back()->with('success', 'Current term updated successfully.');
    }

    public function getSubjects(AcademicTerm $term)
    {
        $subjects = $term->subjects()
            ->withCount(['enrollments', 'grades'])
            ->paginate(15);

        return view('terms.subjects', compact('term', 'subjects'));
    }

    public function getEnrollments(AcademicTerm $term)
    {
        $enrollments = $term->enrollments()
            ->with(['student', 'subject'])
            ->paginate(15);

        return view('terms.enrollments', compact('term', 'enrollments'));
    }

    public function getGrades(AcademicTerm $term)
    {
        $grades = $term->grades()
            ->with(['student', 'subject'])
            ->paginate(15);

        return view('terms.grades', compact('term', 'grades'));
    }

    public function analytics(AcademicTerm $term)
    {
        $stats = [
            'total_subjects' => $term->subjects()->count(),
            'total_students' => $term->enrollments()->distinct('student_id')->count(),
            'total_grades' => $term->grades()->count(),
            'average_grade' => $term->grades()->avg('score'),
            'pass_rate' => $term->grades()
                ->where('score', '>=', 60)
                ->count() / max($term->grades()->count(), 1) * 100,
            'grade_distribution' => [
                'A' => $term->grades()->where('letter_grade', 'A')->count(),
                'B' => $term->grades()->where('letter_grade', 'B')->count(),
                'C' => $term->grades()->where('letter_grade', 'C')->count(),
                'D' => $term->grades()->where('letter_grade', 'D')->count(),
                'F' => $term->grades()->where('letter_grade', 'F')->count()
            ]
        ];

        $topStudents = $term->grades()
            ->select('student_id')
            ->selectRaw('AVG(score) as average_score')
            ->groupBy('student_id')
            ->with('student')
            ->orderByDesc('average_score')
            ->take(10)
            ->get();

        $subjectPerformance = $term->subjects()
            ->withAvg('grades as average_score', 'score')
            ->withCount(['grades as pass_rate' => function ($query) {
                $query->where('score', '>=', 60);
            }])
            ->having('average_score', '>', 0)
            ->get();

        return view('terms.analytics', compact('term', 'stats', 'topStudents', 'subjectPerformance'));
    }
}
