<?php
namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Excel as ExcelFacade;
use App\Exports\CandidatesExport;

class CandidateController extends Controller
{
    /**
     * Process bulk acceptance of candidates
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkAccept(Request $request)
    {
        if (!$request->has('selected')) {
            return redirect()->route('candidates.index')
                ->with('error', 'No candidates were selected.');
        }

        $count = Candidate::whereIn('id', $request->selected)
            ->update(['status' => 'accepted']);

        return redirect()->route('candidates.index')
            ->with('success', $count . ' candidates have been accepted successfully.');
    }

    /**
     * Process bulk deletion of candidates
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(Request $request)
    {
        $candidateIds = $request->input('candidates', []);
        
        if (empty($candidateIds)) {
            return redirect()->route('candidates.index')
                ->with('error', 'No candidates selected for deletion.');
        }
        
        Candidate::whereIn('id', $candidateIds)->delete();
        
        return redirect()->route('candidates.index')
            ->with('success', count($candidateIds) . ' candidates deleted successfully.');
    }
    
    /**
     * Delete a candidate document.
     *
     * @param  \App\Models\CandidateDocument  $document
     * @return \Illuminate\Http\Response
     */
    public function deleteDocument(\App\Models\CandidateDocument $document)
    {
        // Get the candidate ID before deleting the document
        $candidateId = $document->candidate_id;
        
        // Get the file path
        $filePath = $document->file_path;
        
        // Delete the document record from the database
        $document->delete();
        
        // Delete the actual file from storage
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        
        return redirect()->route('candidates.show', $candidateId)
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Download all documents for a candidate.
     * This version doesn't require ZipArchive extension.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function downloadDocuments(Candidate $candidate)
    {
        // Check if candidate has documents
        if ($candidate->documents->isEmpty()) {
            return redirect()->route('candidates.show', $candidate)
                ->with('error', 'No documents available for download.');
        }
        
        // If there's only one document, download it directly
        if ($candidate->documents->count() === 1) {
            $document = $candidate->documents->first();
            $filePath = storage_path('app/public/' . $document->file_path);
            
            if (file_exists($filePath)) {
                return response()->download($filePath, $document->original_filename);
            } else {
                return redirect()->route('candidates.show', $candidate)
                    ->with('error', 'Document file not found.');
            }
        }
        
        // For multiple documents, create a response with links to each document
        return view('candidates.documents', [
            'candidate' => $candidate,
            'documents' => $candidate->documents
        ]);
    }

    /**
     * Display a listing of the candidates.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Explicitly select the fields we need to ensure we're getting the correct data
        $candidates = Candidate::select([
                'id', 
                'first_name', 
                'last_name', 
                'email', 
                'phone', 
                'city', 
                'application_date', 
                'status', 
                'income_level', 
                'specialization',
                'score' // Added score field
            ])
            ->latest()
            ->paginate(10);
            
        return view('candidates.index', compact('candidates'));
    }

    /**
     * Display a listing of accepted candidates.
     *
     * @return \Illuminate\Http\Response
     */
    public function accepted()
    {
        $candidates = Candidate::select(
            'id',
            'first_name',
            'last_name',
            'distance',
            'nationality',
            'academic_year',
            'score',
            'updated_at',
            'training_level' // Keep this for backward compatibility
        )
        ->where('status', 'accepted')
        ->latest()
        ->paginate(10);

        return view('candidates.accepted', compact('candidates'));
    }

    /**
     * Show the form for creating a new candidate.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('candidates.create');
    }

    /**
     * Store a newly created candidate in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cin' => 'required|string|max:255|unique:candidates,cin',
            'email' => 'required|email|max:255|unique:candidates,email',
            'phone' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'distance' => 'required|numeric|min:0',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'training_level' => 'required|string',
            'specialization' => 'required|string',
        ]);

        $validated['application_date'] = now()->format('Y-m-d');

        \App\Models\Candidate::create($validated);

        return redirect()->route('candidates.index')->with('success', 'Candidate created successfully!');
    }

    /**
     * Calculate candidate score based on criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return float
     */
    private function calculateScore(Request $request)
    {
        $score = 0;
        
        // Geographical Criteria
        // Skip distance calculation as the distance field doesn't exist in the database
        // $distance = $request->distance;
        // $score += $distance * 0.01; // 55 km = 0.55 points
        
        // Social Criteria - removed income_level calculation as field doesn't exist in database
        // if ($request->income_level === 'low') {
        //     $score += 10;
        // }
        
        // Academic Criteria - removed training_level calculation as field doesn't exist in database
        // switch ($request->training_level) {
        //     case 'first_year':
        //         $score += 5;
        //         break;
        //     case 'second_year':
        //         $score += 10;
        //         break;
        //     case 'third_year':
        //         $score += 15;
        //         break;
        // }
        
        // Physical Criteria - removed has_disability calculation as field doesn't exist in database
        // if ($request->has_disability) {
        //     $score += 10;
        // }
        
        // Family Criteria - removed family_status calculation as field doesn't exist in database
        // if (is_array($request->family_status) && count($request->family_status) > 0) {
        //     // Add points for each family status condition
        //     if (in_array('orphan', $request->family_status)) {
        //         $score += 5;
        //     }
        //     if (in_array('single_parent', $request->family_status)) {
        //         $score += 3;
        //     }
        //     if (in_array('divorced_deceased', $request->family_status)) {
        //         $score += 4;
        //     }
        //     if (in_array('social_services', $request->family_status)) {
        //         $score += 3;
        //     }
        // }
        
        // Since all score criteria fields have been removed, set a default score
        $score = 3;
        
        return $score;
    }

    /**
     * Display the specified candidate.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        // Eager load the documents relationship
        $candidate->load('documents');
        
        return view('candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified candidate.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidate $candidate)
    {
        $candidate->load(['criteriaWeights']);
        return view('candidates.edit', compact('candidate'));
    }

    /**
     * Update the specified candidate in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
        // Debug logging
        \Log::info('Update request received', [
            'candidate_id' => $candidate->id,
            'request_data' => $request->except(['_token', '_method']),
            'files' => $request->hasFile('supporting_documents') ? 'Files present' : 'No files',
            'remove_documents' => $request->input('remove_documents'),
        ]);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255', "regex:/^[a-zA-ZÃ€-Ã¿\s'-]+$/u"],
            'last_name' => ['required', 'string', 'max:255', "regex:/^[a-zA-ZÃ€-Ã¿\s'-]+$/u"],
            'cin' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]{1,2}[0-9]{1,9}$/'],
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'phone' => 'required|string|max:20|regex:/^[0-9+]*$/',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'distance' => 'required|numeric',
            'income_level' => 'required|string',
            'training_level' => 'required|string',
            'academic_year' => 'required|string',
            'educational_level' => 'required|string',
            'specialization' => 'required|string',
            'physical_condition' => 'required|string',
            'family_status' => 'nullable|array',
            'siblings_count' => 'required|integer',
            'guardian_first_name' => 'required|string|max:255',
            'guardian_last_name' => 'required|string|max:255',
            'guardian_dob' => 'required|date',
            'guardian_profession' => 'required|string|max:255',
            'guardian_phone' => 'nullable|string|max:20|regex:/^[0-9+]*$/',
            // Validation for dynamic criteria
            'criteria' => 'nullable|array',
            'criteria.*.category' => 'required|string|in:geographical,social,academic,physical,family',
            'criteria.*.criteria_id' => 'required|integer|exists:criterias,id',
            'criteria.*.score' => 'nullable|numeric|min:0|max:100',
            'criteria.*.note' => 'nullable|string|max:500',
            'declaration' => 'required',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx,zip|max:10240',
            'remove_documents' => 'nullable|array',
            'remove_documents.*' => 'exists:candidate_documents,id',
        ]);

        $candidateData = $request->except(['_token', '_method', 'supporting_documents', 'declaration', 'remove_documents', 'criteria']);
        
        // Ensure birth_date and guardian_dob are properly formatted
        if (isset($candidateData['birth_date'])) {
            $candidateData['birth_date'] = date('Y-m-d', strtotime($candidateData['birth_date']));
        }
        if (isset($candidateData['guardian_dob'])) {
             $candidateData['guardian_dob'] = date('Y-m-d', strtotime($candidateData['guardian_dob']));
        }

        if (isset($candidateData['family_status']) && is_array($candidateData['family_status'])) {
            $candidateData['family_status'] = implode(',', $candidateData['family_status']);
        }

        $candidate->update($candidateData);
        
        // Sync criteria with the pivot table
        if ($request->has('criteria') && is_array($request->criteria)) {
            $criteriaToSync = [];
            foreach ($request->criteria as $criterion) {
                if (isset($criterion['criteria_id'])) {
                    $criteriaToSync[$criterion['criteria_id']] = [
                        'score' => $criterion['score'] ?? null,
                        'note' => $criterion['note'] ?? null,
                    ];
                }
            }
            $candidate->criteria()->sync($criteriaToSync);
        } else {
            // If no criteria are submitted, detach all existing criteria
            $candidate->criteria()->detach();
        }
        
        // Handle document removals
        if ($request->has('remove_documents')) {
            $documentsToRemove = $candidate->documents()->whereIn('id', $request->remove_documents)->get();
            
            foreach ($documentsToRemove as $document) {
                // Delete the actual file from storage
                if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
                // Delete the document record from the database
                $document->delete();
            }
        }
        
        // Handle new document uploads
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $filename = $candidate->id . '_' . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('candidates/documents', $filename, 'public');
                $candidate->documents()->create([
                    'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'document_type' => null,
                ]);
            }
        }

        // Redirect to Candidates List page with success message
            return redirect()->route('candidates.index')
                ->with('success', 'Candidate updated successfully.');
    }

    /**
     * Remove the specified candidate from storage.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        // dd($request->method(), $request->url()); // Temporary debug line
        $candidate->delete();

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }

    /**
     * Accept the specified candidate.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function accept(Candidate $candidate)
    {
        $candidate->status = 'accepted';
        $candidate->save();

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate accepted successfully.');
    }
    
    /**
     * Convert the specified candidate to a trainee.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    /**
     * Check if a candidate is already in the trainee list
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIfTrainee(Candidate $candidate)
    {
        // Check if a student with the same CIN or email already exists
        $existingStudent = \App\Models\Student::where('cin', $candidate->cin)
            ->orWhere('email', $candidate->email)
            ->first();
            
        if ($existingStudent) {
            return response()->json([
                'exists' => true,
                'student_id' => $existingStudent->id,
                'student_name' => $existingStudent->first_name . ' ' . $existingStudent->last_name
            ]);
        }
        
        return response()->json(['exists' => false]);
    }
    
    /**
     * Convert an accepted candidate to a trainee
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function convertToTrainee(Candidate $candidate)
    {
        try {
        // Check if candidate is already accepted
        if ($candidate->status !== 'accepted') {
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only accepted candidates can be converted to trainees.'
                    ], 422);
                }
            return redirect()->back()
                ->with('error', 'Only accepted candidates can be converted to trainees.');
        }
            
            // Check if this candidate is already in the trainee list
            $existingStudent = \App\Models\Student::where('cin', $candidate->cin)
                ->orWhere('email', $candidate->email)
                ->first();
                
            if ($existingStudent) {
                $message = 'This candidate is already in the trainee list as ' . 
                          $existingStudent->first_name . ' ' . $existingStudent->last_name . 
                          ' (ID: ' . $existingStudent->id . ')';
                
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->back()
                    ->with('warning', $message);
        }
        
        // Create a new student/trainee from the candidate data
        $student = new \App\Models\Student();
        $student->first_name = $candidate->first_name;
        $student->last_name = $candidate->last_name;
            $student->name = $candidate->first_name . ' ' . $candidate->last_name;
        $student->email = $candidate->email;
        $student->phone = $candidate->phone;
        $student->address = $candidate->address;
            $student->place_of_residence = $candidate->city; // Map city to place_of_residence
            $student->date_of_birth = $candidate->birth_date;
            $student->cin = $candidate->cin;
            $student->enrollment_date = now();
            $student->status = 'active';
        
        // Copy additional fields if they exist
        if (isset($candidate->gender)) {
            $student->gender = $candidate->gender;
        }
            
            // Copy academic information with default values
            $student->academic_year = $candidate->academic_year ?? 'First Year';
            $student->specialization = $candidate->specialization ?? 'Not Specified';
            $student->nationality = $candidate->nationality ?? 'Moroccan';
            $student->educational_level = $candidate->educational_level ?? 'specialized_technician';
        
        // Save the new student
        $student->save();
        
        // Update candidate status to indicate conversion
        $candidate->status = 'converted';
        $candidate->save();
        
            // Always redirect with a success message
        return redirect()->route('candidates.accepted')
                ->with('success', 'Candidate successfully converted to trainee and moved to Students.');
                
        } catch (\Exception $e) {
            \Log::error('Error converting candidate to trainee: ' . $e->getMessage());
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while converting the candidate.'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'An error occurred while converting the candidate.');
        }
    }
    
    /**
     * Reject the specified candidate.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function reject(Candidate $candidate)
    {
        $candidate->status = 'rejected';
        $candidate->save();
        
        return redirect()->route('candidates.accepted')
            ->with('success', 'Candidate rejected successfully.');
    }

    /**
     * Bulk convert selected accepted candidates to trainees.
     */
    public function bulkConvert(Request $request)
    {
        $candidateIds = $request->input('selected', []);
        if (empty($candidateIds)) {
            return redirect()->back()->with('error', 'No candidates selected for conversion.');
        }

        $converted = 0;
        foreach (Candidate::whereIn('id', $candidateIds)->where('status', 'accepted')->get() as $candidate) {
            // Check if a student with the same email already exists
            $existingStudent = \App\Models\Student::where('email', $candidate->email)->first();

            if ($existingStudent) {
                // Skip conversion if a student with the same email exists
                continue;
            }

            $student = new \App\Models\Student();
            $student->first_name = $candidate->first_name;
            $student->last_name = $candidate->last_name;
            $student->name = $candidate->first_name . ' ' . $candidate->last_name;
            $student->email = $candidate->email;
            $student->phone = $candidate->phone;
            $student->address = $candidate->address;
            $student->place_of_residence = $candidate->city; // Map city to place_of_residence
            $student->date_of_birth = $candidate->birth_date;
            $student->cin = $candidate->cin;
            $student->enrollment_date = now();
            $student->status = 'active';

            if (isset($candidate->gender)) {
                $student->gender = $candidate->gender;
            }

            $student->save();
            $candidate->status = 'converted';
            $candidate->save();
            $converted++;
        }

        return redirect()->route('candidates.accepted')->with('success', $converted . ' candidates converted to trainees.');
    }

    /**
     * Bulk reject selected accepted candidates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkReject(Request $request)
    {
        $candidateIds = $request->input('selected', []);
        if (empty($candidateIds)) {
            return redirect()->back()->with('error', 'No candidates selected for rejection.');
        }

        $count = Candidate::whereIn('id', $candidateIds)
            ->where('status', 'accepted')
            ->update(['status' => 'rejected']);

        return redirect()->route('candidates.accepted')
            ->with('success', $count . ' candidates rejected successfully.');
    }

    /**
     * Export candidates to PDF
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPdf()
    {
        try {
            $candidates = Candidate::all();
            $pdf = Pdf::loadView('exports.candidates-pdf', compact('candidates'));
            return $pdf->download('candidates-list-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export candidates to Excel
     * 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel()
    {
        try {
            $export = new \App\Exports\CandidatesExport();
            return $export->exportToExcel();
        } catch (\Exception $e) {
            \Log::error('Excel Export Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate Excel file: ' . $e->getMessage());
        }
    }

    /**
     * Export candidates to CSV
     * 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCsv()
    {
        try {
            $export = new \App\Exports\CandidatesExport();
            return $export->exportToCsv();
        } catch (\Exception $e) {
            \Log::error('CSV Export Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate CSV file: ' . $e->getMessage());
        }
    }
}