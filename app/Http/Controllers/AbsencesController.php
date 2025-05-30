<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Student;
use Illuminate\Http\Request;

class AbsencesController extends Controller
{
    public function index()
    {
        $absences = Absence::with('student')->latest()->paginate(10);
        return view('absences.index', compact('absences'));
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
            'date' => 'required|date',
            'type' => 'required|in:excused,unexcused,late',
            'duration' => 'required_if:type,late|nullable|integer|min:1',
            'reason' => 'required|string|max:1000',
        ]);

        Absence::create($validated);

        return redirect()->route('absences.index')
            ->with('success', 'Absence record created successfully.');
    }

    public function show(Absence $absence)
    {
        return view('absences.show', compact('absence'));
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
            'date' => 'required|date',
            'type' => 'required|in:excused,unexcused,late',
            'duration' => 'required_if:type,late|nullable|integer|min:1',
            'reason' => 'required|string|max:1000',
        ]);

        $absence->update($validated);

        return redirect()->route('absences.index')
            ->with('success', 'Absence record updated successfully.');
    }

    public function destroy(Absence $absence)
    {
        $absence->delete();

        return redirect()->route('absences.index')
            ->with('success', 'Absence record deleted successfully.');
    }
}
