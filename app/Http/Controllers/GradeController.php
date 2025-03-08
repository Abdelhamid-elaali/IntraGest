<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\User;
use App\Models\Subject;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::with(['student', 'subject', 'academicTerm'])
            ->latest()
            ->paginate(15);
            
        return view('grades.index', compact('grades'));
    }

    public function create()
    {
        $students = User::students()->get();
        $subjects = Subject::active()->get();
        $terms = AcademicTerm::current()->get();
        
        return view('grades.create', compact('students', 'subjects', 'terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'score' => 'required|numeric|min:0|max:100',
            'assessment_type' => 'required|string',
            'weight' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string|max:500'
        ]);

        $grade = Grade::create($validated + [
            'grader_id' => auth()->id(),
            'letter_grade' => Grade::calculateLetterGrade($validated['score'])
        ]);

        return redirect()->route('grades.show', $grade)
            ->with('success', 'Grade recorded successfully.');
    }

    public function show(Grade $grade)
    {
        return view('grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $students = User::students()->get();
        $subjects = Subject::active()->get();
        $terms = AcademicTerm::current()->get();
        
        return view('grades.edit', compact('grade', 'students', 'subjects', 'terms'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'score' => 'required|numeric|min:0|max:100',
            'assessment_type' => 'required|string',
            'weight' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string|max:500'
        ]);

        $grade->update($validated + [
            'letter_grade' => Grade::calculateLetterGrade($validated['score'])
        ]);

        return redirect()->route('grades.show', $grade)
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')
            ->with('success', 'Grade deleted successfully.');
    }

    public function analytics()
    {
        $topStudents = User::students()
            ->withAvg('grades as average_score', 'score')
            ->orderByDesc('average_score')
            ->take(10)
            ->get();

        $subjectPerformance = Subject::withAvg('grades as average_score', 'score')
            ->withCount(['grades as pass_rate' => function ($query) {
                $query->where('score', '>=', 60);
            }])
            ->having('average_score', '>', 0)
            ->get();

        return view('grades.analytics', compact('topStudents', 'subjectPerformance'));
    }

    public function showStudent(User $student)
    {
        $grades = $student->grades()
            ->with(['subject', 'academicTerm'])
            ->latest()
            ->paginate(15);
            
        return view('grades.student', compact('student', 'grades'));
    }

    public function showSubject(Subject $subject)
    {
        $grades = $subject->grades()
            ->with(['student', 'academicTerm'])
            ->latest()
            ->paginate(15);
            
        return view('grades.subject', compact('subject', 'grades'));
    }
}
