<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function index()
    {
        $absences = Absence::with(['user', 'approver'])->latest()->paginate(10);
        return view('absences.index', compact('absences'));
    }

    public function create()
    {
        return view('absences.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $absence = new Absence($validated);
        $absence->user_id = auth()->id();
        $absence->status = 'pending';
        $absence->save();

        return redirect()->route('absences.index')->with('success', 'Absence request submitted successfully.');
    }

    public function show(Absence $absence)
    {
        return view('absences.show', compact('absence'));
    }

    public function edit(Absence $absence)
    {
        return view('absences.edit', compact('absence'));
    }

    public function update(Request $request, Absence $absence)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $absence->update($validated);

        return redirect()->route('absences.index')->with('success', 'Absence request updated successfully.');
    }

    public function destroy(Absence $absence)
    {
        $absence->delete();
        return redirect()->route('absences.index')->with('success', 'Absence request deleted successfully.');
    }
}
