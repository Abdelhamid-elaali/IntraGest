@extends('layouts.app')

@section('title', 'Candidate Details')

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-xs font-medium;
    }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }
    .status-accepted { @apply bg-green-100 text-green-800; }
    .status-rejected { @apply bg-red-100 text-red-800; }
    .status-converted { @apply bg-purple-100 text-purple-800; }
    
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .tab-button {
        @apply px-4 py-2 text-sm font-medium rounded-t-lg transition-colors duration-200;
    }
    .tab-button.active {
        @apply bg-white text-blue-600 border-b-2 border-blue-600;
    }
    .tab-button:not(.active) {
        @apply text-gray-500 hover:text-gray-700 hover:bg-gray-100;
    }
    .info-card {
        @apply bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200;
    }
    .info-label {
        @apply text-xs font-medium text-gray-500 uppercase tracking-wider;
    }
    .info-value {
        @apply mt-1 text-sm text-gray-900;
    }
</style>
@endpush

@section('content')
<div x-data="{
        activeTab: 'overview',
        showDocumentModal: false,
        selectedDocument: null,
        openDocument(document) {
            this.selectedDocument = document;
            this.showDocumentModal = true;
        },
        closeDocumentModal() {
            this.showDocumentModal = false;
            this.selectedDocument = null;
        }
    }" class="space-y-6">
    
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ $candidate->first_name }} {{ $candidate->last_name }}</h1>
                <span class="status-badge status-{{ $candidate->status }}">
                    {{ ucfirst($candidate->status) }}
                </span>
            </div>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L4 11.414V18a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('candidates.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Candidates</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Details</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('candidates.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
            <div class="relative inline-block text-left group" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open">
                    Actions
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                    <div class="py-1" role="none">
                        <a href="{{ route('candidates.edit', $candidate) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                            <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Candidate
                        </a>
                        @if($candidate->status == 'pending')
                        <form action="{{ route('candidates.accept', $candidate) }}" method="POST" class="block w-full text-left">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-green-700 hover:bg-green-50" role="menuitem">
                                <svg class="mr-3 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Accept Candidate
                            </button>
                        </form>
                        @endif
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('candidates.destroy', $candidate) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this candidate? This action cannot be undone.')" class="block w-full text-left">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50" role="menuitem">
                                <svg class="mr-3 h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 22H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Candidate
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Contents -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Overview Tab -->
        <div x-show="activeTab === 'overview'" class="tab-content active">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Candidate Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Personal details and application information.</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Full name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $candidate->first_name }} {{ $candidate->last_name }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">CIN</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $candidate->cin ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $candidate->email }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Phone number</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $candidate->phone ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $candidate->address ?? 'N/A' }}
                            @if($candidate->city)
                                , {{ $candidate->city }}
                            @endif
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $candidate->birth_date ? \Carbon\Carbon::parse($candidate->birth_date)->format('M d, Y') : 'N/A' }}
                            @if($candidate->birth_date)
                                ({{ \Carbon\Carbon::parse($candidate->birth_date)->age }} years old)
                            @endif
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($candidate->gender == 'male')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Male
                                </span>
                            @elseif($candidate->gender == 'female')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    Female
                                </span>
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nationality</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $candidate->nationality ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Application Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $candidate->created_at->format('M d, Y') }} ({{ $candidate->created_at->diffForHumans() }})
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Training Level</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ ucfirst(str_replace('_', ' ', $candidate->training_level)) ?? 'N/A' }}
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Specialization</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ ucfirst(str_replace('_', ' ', $candidate->specialization)) ?? 'N/A' }}
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="status-badge status-{{ $candidate->status }}">
                                {{ ucfirst($candidate->status) }}
                            </span>
                        </dd>
                    </div>
                    @if($candidate->notes)
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="prose max-w-none">
                                {!! nl2br(e($candidate->notes)) !!}
                            </div>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
                
                <!-- Candidate Details Section -->
                <div class="md:w-2/3 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Contact Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Email Address</h4>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-800">{{ $candidate->email }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Phone Number</h4>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-gray-800">{{ $candidate->phone }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Address</h4>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <p class="text-gray-800">{{ $candidate->address }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">City</h4>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-gray-800">{{ $candidate->city }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4 pb-2 border-b border-gray-200">Application Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500">Application Date</h4>
                            <div class="mt-1 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-800">{{ date('d/m/Y', strtotime($candidate->application_date)) }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500">Birth Date</h4>
                            <div class="mt-1 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-800">{{ $candidate->birth_date ? date('d/m/Y', strtotime($candidate->birth_date)) : 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($candidate->notes)
                    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4 pb-2 border-b border-gray-200">Notes</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-gray-700">{{ $candidate->notes }}</p>
                    </div>
                    @endif
                    
                    <!-- Supporting Documents Section -->
                    <div class="flex justify-between items-center mt-6 mb-4 pb-2 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Supporting Documents</h3>
                        @if($candidate->documents->count() > 0)
                        <a href="{{ route('candidates.download-documents', $candidate) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download All
                        </a>
                        @endif
                    </div>
                    
                    @if($candidate->documents->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($candidate->documents as $document)
                                <div class="border border-gray-200 rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
                                    @php
                                        $fileType = strtolower(pathinfo($document->original_filename, PATHINFO_EXTENSION));
                                        $isImage = in_array($fileType, ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp
                                    
                                    <!-- Image Preview (if applicable) -->
                                    @if($isImage)
                                        <div class="mb-3">
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="block">
                                                <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->original_filename }}" class="w-full h-32 object-cover rounded-md border border-gray-200 hover:opacity-90 transition-opacity duration-200" />
                                            </a>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-start space-x-3">
                                        <!-- Document icon based on file type -->
                                        <div class="flex-shrink-0">
                                            @php
                                                $iconClass = 'text-gray-400';
                                                
                                                if (in_array($fileType, ['pdf'])) {
                                                    $iconClass = 'text-red-500';
                                                } elseif (in_array($fileType, ['doc', 'docx'])) {
                                                    $iconClass = 'text-blue-500';
                                                } elseif (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
                                                    $iconClass = 'text-green-500';
                                                } elseif (in_array($fileType, ['xls', 'xlsx', 'csv'])) {
                                                    $iconClass = 'text-emerald-500';
                                                }
                                            @endphp
                                            
                                            <svg class="h-10 w-10 {{ $iconClass }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- Document details -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $document->original_filename }}">
                                                {{ $document->original_filename }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ strtoupper(pathinfo($document->original_filename, PATHINFO_EXTENSION)) }} • {{ number_format($document->file_size / 1024, 0) }} KB
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Added {{ $document->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Document actions -->
                                    <div class="mt-3 flex justify-end space-x-2">
                                        <button @click="$dispatch('open-document', { 
    filename: '{{ $document->original_filename }}',
    url: '{{ asset('storage/' . $document->file_path) }}',
    size: {{ $document->file_size }},
    uploadedAt: '{{ $document->created_at->format('M d, Y') }}',
    type: '{{ pathinfo($document->original_filename, PATHINFO_EXTENSION) }}'
})" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
    <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
    </svg>
    Preview
</button>
                                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <form action="{{ route('candidates.delete-document', $document) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 text-center">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-600">No supporting documents have been uploaded for this candidate.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Handle document download with alert
     */
    function handleDocumentDownload(event, filename, documentId) {
        // Prevent default link behavior
        event.preventDefault();
        
        // Show the download alert
        showDocumentDownloadAlert(filename);
        
        // Construct the download URL
        const downloadUrl = '/documents/' + documentId + '/download';
        
        // For direct download, open in a new window that will be automatically closed
        const downloadWindow = window.open(downloadUrl, '_blank');
        
        // If popup is blocked, fallback to direct navigation
        if (!downloadWindow) {
            window.location.href = downloadUrl;
        } else {
            // Close the window after download is initiated (for browsers that allow it)
            setTimeout(() => {
                try {
                    if (downloadWindow) downloadWindow.close();
                } catch (e) {
                    // Ignore errors from cross-origin restrictions
                }
            }, 1000);
        }
    }

    function showDocumentDownloadAlert(filename) {
        // Create a container for the x-alert if it doesn't exist
        let alertContainer = document.getElementById('dynamic-alerts-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.id = 'dynamic-alerts-container';
            alertContainer.className = 'fixed bottom-4 right-4 z-50 max-w-md';
            document.body.appendChild(alertContainer);
        }
        
        // Generate a unique ID for this alert
        const alertId = 'alert-' + Date.now();
        
        // Create the x-alert element using the component structure
        const alertElement = document.createElement('div');
        alertElement.id = alertId;
        alertElement.className = 'mb-4';
        alertElement.innerHTML = `
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow-lg flex items-start" role="alert">
                <div class="flex-shrink-0 mr-3">
                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium">Document Downloaded</p>
                    <p class="text-sm">The document "${filename}" is being downloaded. Some file types may not be viewable in the browser and will need to be opened with appropriate software.</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg p-1.5 hover:bg-blue-200 inline-flex h-6 w-6 items-center justify-center" onclick="dismissAlert('${alertId}')">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        // Add the alert to the container
        alertContainer.appendChild(alertElement);
        
        // Auto-dismiss after 6 seconds
        setTimeout(() => {
            dismissAlert(alertId);
        }, 6000);
    }
    
    function dismissAlert(alertId) {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                alertElement.remove();
            }, 500);
        }
    }
</script>

<!-- Document Preview Modal -->
<div x-data="documentPreview" 
     @open-document.window="openDocument($event.detail)"
     x-cloak>
    <!-- Overlay -->
    <div x-show="isOpen" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         @click.self="closeDocument">
        
        <!-- Modal Content -->
        <div x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900" x-text="currentDocument?.filename || 'Document Preview'"></h3>
                <button @click="closeDocument" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Document Content -->
            <div class="flex-1 overflow-auto p-6">
                <template x-if="isImage">
                    <div class="flex justify-center">
                        <img :src="currentDocument?.url" :alt="currentDocument?.filename" class="max-h-[60vh] max-w-full object-contain">
                    </div>
                </template>
                
                <template x-if="isPdf">
                    <div class="h-[60vh] w-full">
                        <iframe :src="currentDocument?.url + '#toolbar=0&view=FitH'" class="w-full h-full border-0"></iframe>
                    </div>
                </template>
                
                <template x-if="!isImage && !isPdf">
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 text-gray-400" x-html="getFileIcon(currentDocument?.type || '')"></div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Preview not available</h3>
                        <p class="mt-1 text-sm text-gray-500">This file type cannot be previewed in the browser.</p>
                    </div>
                </template>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <span x-text="currentDocument?.type ? currentDocument.type.toUpperCase() : 'FILE'"></span>
                    <span> • </span>
                    <span x-text="formatFileSize(currentDocument?.size || 0)"></span>
                    <span x-show="currentDocument?.uploadedAt"> • Uploaded <span x-text="currentDocument.uploadedAt"></span></span>
                </div>
                <div class="flex space-x-3">
                    <a :href="currentDocument?.url" download class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Download
                    </a>
                    <a :href="currentDocument?.url" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        Open in new tab
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('documentPreview', () => ({
        isOpen: false,
        currentDocument: null,
        isImage: false,
        isPdf: false,
        
        init() {
            // Handle escape key to close modal
            document.addEventListener('keydown', (e) => {
                if (this.isOpen && e.key === 'Escape') {
                    this.closeDocument();
                }
            });
        },
        
        openDocument(document) {
            this.currentDocument = document;
            this.isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(
                this.getFileExtension(document.filename).toLowerCase()
            );
            this.isPdf = this.getFileExtension(document.filename).toLowerCase() === 'pdf';
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeDocument() {
            this.isOpen = false;
            this.currentDocument = null;
            document.body.style.overflow = '';
        },
        
        getFileExtension(filename) {
            return filename.split('.').pop();
        },
        
        formatFileSize(bytes) {
            if (!bytes) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.min(Math.floor(Math.log(bytes) / Math.log(k)), sizes.length - 1);
            return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
        },
        
        getFileIcon(ext) {
            const extMap = {
                pdf: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-red-500' },
                doc: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-blue-500' },
                docx: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-blue-500' },
                xls: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-green-500' },
                xlsx: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-green-500' },
                jpg: { icon: 'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z', color: 'text-purple-500' },
                jpeg: { icon: 'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z', color: 'text-purple-500' },
                png: { icon: 'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z', color: 'text-purple-500' },
                gif: { icon: 'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z', color: 'text-purple-500' },
                default: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-gray-500' }
            };
            const icon = extMap[ext.toLowerCase()] || extMap.default;
            return `<svg class="h-12 w-12 ${icon.color}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="${icon.icon}" clip-rule="evenodd" />
            </svg>`;
        }
    }));
});
</script>
@endpush

@endsection
