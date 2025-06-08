<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::select([
            'id', 'name', 'first_name', 'last_name', 'email', 'phone', 'address', 
            'place_of_residence', 'date_of_birth', 'enrollment_date', 'status',
            'academic_year', 'specialization', 'nationality', 'educational_level', 'cin', 'gender',
            'created_at', 'updated_at'
        ])->latest()->paginate(10);
        
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
            'first_name' => ['required', 'string', 'max:255', "regex:/^[a-zA-ZÀ-ÿ\s'-]+$/u"],
            'last_name' => ['required', 'string', 'max:255', "regex:/^[a-zA-ZÀ-ÿ\s'-]+$/u"],
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
        $count = Student::count();

        // Dispatch event for trainee added
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Trainee added successfully.',
                'count' => $count
            ]);
        }

        return redirect()->route('students.index')
            ->with([
                'success' => 'Trainee added successfully.',
                'trainee_added' => true,
                'trainee_count' => $count
            ]);
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
            'first_name' => ['required', 'string', 'max:255', "regex:/^[a-zA-ZÀ-ÿ\s'-]+$/u"],
            'last_name' => ['required', 'string', 'max:255', "regex:/^[a-zA-ZÀ-ÿ\s'-]+$/u"],
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
        $count = Student::count();

        // Dispatch event for trainee removed
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Trainee deleted successfully.',
                'count' => $count,
                'redirect' => route('students.index')
            ]);
        }

        return redirect()->route('students.index')
            ->with([
                'success' => 'Trainee deleted successfully.',
                'trainee_removed' => true,
                'trainee_count' => $count
            ]);
    }
    
    /**
     * Remove multiple students from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:students,id'
        ]);

        $count = count($request->selected);
        Student::whereIn('id', $request->selected)->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$count} " . str('trainee')->plural($count) . ".",
                'redirect' => route('students.index')
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', "Successfully deleted {$count} " . str('trainee')->plural($count) . ".");
    }
}
