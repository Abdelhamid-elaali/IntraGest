<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $candidates = Candidate::select('id', 'first_name', 'last_name', 'email', 'phone', 'city', 'application_date', 'status')
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
        $candidates = Candidate::where('status', 'accepted')->latest()->paginate(10);
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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // academic_year field removed - not in database
            // specialization field removed - not in database
            'nationality' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            // distance field removed - not in database
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:candidates,email',
            // income_level field removed - not in database
            // training_level field removed - not in database
            // has_disability field removed - not in database
            // family_status field removed - not in database
            // family_status.* field removed - not in database
            'gender' => 'required|in:male,female',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,txt,xls,xlsx,csv|max:10240',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_profession' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
        ]);

        // Calculate score based on criteria
        $score = $this->calculateScore($request);
        
        $candidateData = $request->all();
        // Remove fields that don't exist in the database
        if (isset($candidateData['name'])) {
            unset($candidateData['name']);
        }
        if (isset($candidateData['nationality'])) {
            unset($candidateData['nationality']);
        }
        if (isset($candidateData['distance'])) {
            unset($candidateData['distance']);
        }
        if (isset($candidateData['income_level'])) {
            unset($candidateData['income_level']);
        }
        if (isset($candidateData['has_disability'])) {
            unset($candidateData['has_disability']);
        }
        if (isset($candidateData['training_level'])) {
            unset($candidateData['training_level']);
        }
        if (isset($candidateData['academic_year'])) {
            unset($candidateData['academic_year']);
        }
        if (isset($candidateData['specialization'])) {
            unset($candidateData['specialization']);
        }
        if (isset($candidateData['family_status'])) {
            unset($candidateData['family_status']);
        }
        if (isset($candidateData['guardian_name'])) {
            unset($candidateData['guardian_name']);
        }
        if (isset($candidateData['guardian_profession'])) {
            unset($candidateData['guardian_profession']);
        }
        if (isset($candidateData['guardian_phone'])) {
            unset($candidateData['guardian_phone']);
        }
        if (isset($candidateData['supporting_documents'])) {
            unset($candidateData['supporting_documents']);
        }
        
        // Remove score field as it doesn't exist in the database
        // $candidateData['score'] = $score;
        $candidateData['status'] = 'pending';
        
        // Add default birth_date value since it's required in the database but missing in the form
        if (!isset($candidateData['birth_date'])) {
            $candidateData['birth_date'] = now()->format('Y-m-d');
        }
        
        // Add default city value since it's required in the database but missing in the form
        if (!isset($candidateData['city'])) {
            $candidateData['city'] = 'Unknown';
        }
        
        // Add default application_date value since it's required in the database but missing in the form
        if (!isset($candidateData['application_date'])) {
            $candidateData['application_date'] = now()->format('Y-m-d');
        }

        // Create the candidate record
        $candidate = Candidate::create($candidateData);
        
        // Handle document uploads
        if ($request->hasFile('supporting_documents')) {
            $documentPaths = [];
            foreach ($request->file('supporting_documents') as $file) {
                // Generate a unique filename with original extension
                $filename = $candidate->id . '_' . time() . '_' . $file->getClientOriginalName();
                
                // Store the file in the public storage
                $path = $file->storeAs('candidates/documents', $filename, 'public');
                $documentPaths[] = $path;
                
                // Store document information using the CandidateDocument model
                $candidate->documents()->create([
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'document_type' => null, // You can add document type detection logic here if needed
                ]);
            }
        }

        return redirect()->route('candidates.index')
            ->with('success', 'Candidate added successfully.');
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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cin' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:255',
            'place_of_residence' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'distance' => 'required|numeric',
            'income_level' => 'required|string',
            'training_level' => 'required|string',
            'educational_level' => 'required|string',
            'specialization' => 'required|string',
            'physical_condition' => 'required|string',
            'family_status' => 'nullable|array',
            'siblings_count' => 'required|integer',
            'guardian_name' => 'required|string|max:255',
            'guardian_dob' => 'required|date',
            'guardian_profession' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'declaration' => 'required',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx,zip|max:10240',
        ]);

        $updateData = $request->except(['_token', '_method', 'supporting_documents', 'declaration']);
        if (isset($updateData['family_status']) && is_array($updateData['family_status'])) {
            $updateData['family_status'] = implode(',', $updateData['family_status']);
        }

        $candidate->update($updateData);

        // Handle document uploads
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
    public function convertToTrainee(Candidate $candidate)
    {
        // Check if candidate is already accepted
        if ($candidate->status !== 'accepted') {
            return redirect()->back()
                ->with('error', 'Only accepted candidates can be converted to trainees.');
        }
        
        // Create a new student/trainee from the candidate data
        $student = new \App\Models\Student();
        $student->first_name = $candidate->first_name;
        $student->last_name = $candidate->last_name;
        $student->email = $candidate->email;
        $student->phone = $candidate->phone;
        $student->address = $candidate->address;
        $student->city = $candidate->city;
        $student->birth_date = $candidate->birth_date;
        
        // Copy additional fields if they exist
        if (isset($candidate->gender)) {
            $student->gender = $candidate->gender;
        }
        
        // Save the new student
        $student->save();
        
        // Update candidate status to indicate conversion
        $candidate->status = 'converted';
        $candidate->save();
        
        return redirect()->route('candidates.accepted')
            ->with('success', 'Candidate successfully converted to trainee.');
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

// Removed duplicate methods - these are already defined earlier in the controller
// 
/**
 * Download a specific document.
 *
 * @param  \App\Models\CandidateDocument  $document
 * @return \Illuminate\Http\Response
 */
public function downloadDocument(CandidateDocument $document)
{
    $filePath = storage_path('app/public/' . $document->file_path);
    
    if (file_exists($filePath)) {
        // Set appropriate headers for download
        $headers = [
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => 'attachment; filename="' . $document->original_filename . '"',
            'Content-Length' => filesize($filePath),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];
        
        // Return the file as a download response
        return response()->file($filePath, $headers);
    } else {
        return redirect()->back()
            ->with('error', 'Document file not found.');
    }
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
            $student = new \App\Models\Student();
            $student->first_name = $candidate->first_name;
            $student->last_name = $candidate->last_name;
            $student->email = $candidate->email;
            $student->phone = $candidate->phone;
            $student->address = $candidate->address;
            $student->city = $candidate->city;
            $student->birth_date = $candidate->birth_date;
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
}
