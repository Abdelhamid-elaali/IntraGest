<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cin' => 'required|string|max:20',
            'academic_year' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'educational_level' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'place_of_residence' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:students,email',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
        ]);
        
        // Combine first_name and last_name into name field
        $data = $request->all();
        $data['name'] = $request->first_name . ' ' . $request->last_name;
        
        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Trainee added successfully.');
    }

    /**
     * Display the specified student.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cin' => 'required|string|max:20',
            'academic_year' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'educational_level' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'place_of_residence' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
        ]);
        
        // Combine first_name and last_name into name field
        $data = $request->all();
        $data['name'] = $request->first_name . ' ' . $request->last_name;
        
        $student->update($data);

        return redirect()->route('students.index')
            ->with('success', 'Trainee updated successfully.');
    }

    /**
     * Remove the specified student from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Trainee deleted successfully.');
    }
}
