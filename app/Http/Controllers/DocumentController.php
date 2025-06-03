<?php

namespace App\Http\Controllers;

use App\Models\CandidateDocument;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Download a specific document.
     *
     * @param  \App\Models\CandidateDocument  $document
     * @return \Illuminate\Http\Response
     */
    public function download(CandidateDocument $document)
    {
        $filePath = storage_path('app/public/' . $document->file_path);
        
        if (file_exists($filePath)) {
            return response()->download($filePath, $document->original_filename);
        } else {
            return redirect()->back()
                ->with('error', 'Document file not found.');
        }
    }
}
