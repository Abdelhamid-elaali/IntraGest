<?php

namespace App\Http\Controllers;

use App\Models\AbsenceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceTypeController extends Controller
{
    /**
     * Display a listing of the absence types.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $absenceTypes = AbsenceType::orderBy('name')->get();
        return view('absence-types.index', compact('absenceTypes'));
    }

    /**
     * Show the form for creating a new absence type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('absence-types.create');
    }

    /**
     * Store a newly created absence type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:absence_types',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'requires_documentation' => 'boolean',
            'max_days_allowed' => 'nullable|integer|min:1',
        ]);

        AbsenceType::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'requires_documentation' => $request->has('requires_documentation'),
            'max_days_allowed' => $request->max_days_allowed,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('absence-types.index')
            ->with('success', 'Absence type created successfully.');
    }

    /**
     * Display the specified absence type.
     *
     * @param  \App\Models\AbsenceType  $absenceType
     * @return \Illuminate\Http\Response
     */
    public function show(AbsenceType $absenceType)
    {
        return view('absence-types.show', compact('absenceType'));
    }

    /**
     * Show the form for editing the specified absence type.
     *
     * @param  \App\Models\AbsenceType  $absenceType
     * @return \Illuminate\Http\Response
     */
    public function edit(AbsenceType $absenceType)
    {
        return view('absence-types.edit', compact('absenceType'));
    }

    /**
     * Update the specified absence type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AbsenceType  $absenceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AbsenceType $absenceType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:absence_types,name,' . $absenceType->id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'requires_documentation' => 'boolean',
            'max_days_allowed' => 'nullable|integer|min:1',
        ]);

        $absenceType->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'requires_documentation' => $request->has('requires_documentation'),
            'max_days_allowed' => $request->max_days_allowed,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('absence-types.index')
            ->with('success', 'Absence type updated successfully.');
    }

    /**
     * Remove the specified absence type from storage.
     *
     * @param  \App\Models\AbsenceType  $absenceType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AbsenceType $absenceType)
    {
        // Check if this absence type is being used before deletion
        if ($absenceType->absences()->count() > 0) {
            return redirect()->route('absence-types.index')
                ->with('error', 'Cannot delete this absence type as it is associated with existing absences.');
        }

        $absenceType->delete();

        return redirect()->route('absence-types.index')
            ->with('success', 'Absence type deleted successfully.');
    }
}
