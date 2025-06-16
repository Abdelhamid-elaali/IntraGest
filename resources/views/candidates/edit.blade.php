@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Candidate</h1>
        <p class="text-gray-600">Update the candidate's information below.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form id="candidateForm" action="{{ route('candidates.update', $candidate) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', isset($candidate->first_name) ? $candidate->first_name : '') }}" placeholder="Enter candidate's first name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required pattern="^[a-zA-ZÀ-ÿ\\s'-]+$" title="Only alphabetic characters, spaces, hyphens, and apostrophes are allowed">
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Family Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', isset($candidate->last_name) ? $candidate->last_name : '') }}" placeholder="Enter candidate's family name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required pattern="^[a-zA-ZÀ-ÿ\\s'-]+$" title="Only alphabetic characters, spaces, hyphens, and apostrophes are allowed">
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cin" class="block text-sm font-medium text-gray-700 mb-1">CIN ID</label>
                    <input type="text" name="cin" id="cin" value="{{ old('cin', isset($candidate->cin) ? $candidate->cin : '') }}" placeholder="Enter candidate's CIN ID" pattern="^[A-Za-z]{1,2}[0-9]{1,9}$" title="CIN must start with 1 or 2 letters followed by 1 to 9 numbers (e.g., AB123456789)" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    <p class="text-xs text-gray-500 mt-1">Only letters and numbers are allowed, no symbols.</p>
                    @error('cin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $candidate->email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $candidate->phone) }}" placeholder="Enter candidate's phone number" pattern="[0-9+]*" inputmode="tel" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" oninput="this.value = this.value.replace(/[^0-9+]/g, '');" required>
                    <p class="text-xs text-gray-500 mt-1">Only numbers and the plus (+) symbol are allowed</p>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <div class="mt-2 flex items-center space-x-6">
                        <div class="flex items-center">
                            <input id="gender-male" name="gender" type="radio" value="male" {{ old('gender', $candidate->gender) == 'male' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="gender-male" class="ml-2 block text-sm text-gray-700">Male</label>
                        </div>
                        <div class="flex items-center">
                            <input id="gender-female" name="gender" type="radio" value="female" {{ old('gender', $candidate->gender) == 'female' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="gender-female" class="ml-2 block text-sm text-gray-700">Female</label>
                        </div>
                    </div>
                    @error('gender')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $candidate->birth_date ? $candidate->birth_date->format('Y-m-d') : '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('birth_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="place_of_residence" class="block text-sm font-medium text-gray-700 mb-1">Address / Place of Residence</label>
                    <input type="text" name="place_of_residence" id="place_of_residence" value="{{ old('place_of_residence', isset($candidate->place_of_residence) ? $candidate->place_of_residence : '') }}" placeholder="Enter candidate's place of residence" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('place_of_residence')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $candidate->city) }}" placeholder="Enter candidate's city" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                    <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $candidate->nationality) }}" placeholder="Enter candidate's nationality" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('nationality')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Geographical Criteria -->
                <div>
                    <label for="distance" class="block text-sm font-medium text-gray-700 mb-1">Distance from Institution (km)</label>
                    <input type="number" name="distance" id="distance" value="{{ old('distance', $candidate->distance) }}" min="0" step="0.1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('distance')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Criteria -->
                <div>
                    <label for="training_level" class="block text-sm font-medium text-gray-700 mb-1">Training Level</label>
                    <select name="training_level" id="training_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Training Level</option>
                        <option value="first_year" {{ old('training_level', $candidate->training_level) == 'first_year' ? 'selected' : '' }}>First Year</option>
                        <option value="second_year" {{ old('training_level', $candidate->training_level) == 'second_year' ? 'selected' : '' }}>Second Year</option>
                        <option value="third_year" {{ old('training_level', $candidate->training_level) == 'third_year' ? 'selected' : '' }}>Third Year</option>
                    </select>
                    @error('training_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                    <select name="specialization" id="specialization" class="select2-specialization w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select a specialization</option>
                        
                        <!-- Technicien Spécialisé -->
                        <optgroup label="Technicien Spécialisé">
                            <!-- Gestion des entreprise -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Gestion des entreprise">
                                <option value="GE_1A" {{ old('specialization', $candidate->specialization) == 'GE_1A' ? 'selected' : '' }}>Gestion des Entreprises 1ère année</option>
                                <option value="GE_CM_2A" {{ old('specialization', $candidate->specialization) == 'GE_CM_2A' ? 'selected' : '' }}>Gestion des Entreprises option Commerce et Marketing (2ème année)</option>
                                <option value="GE_CM_3A" {{ old('specialization', $candidate->specialization) == 'GE_CM_3A' ? 'selected' : '' }}>Gestion des Entreprises option Commerce et Marketing (3ème année)</option>
                                <option value="GE_CF_2A" {{ old('specialization', $candidate->specialization) == 'GE_CF_2A' ? 'selected' : '' }}>Gestion des Entreprises option Comptabilité et Finance (2ème année)</option>
                                <option value="GE_CF_3A" {{ old('specialization', $candidate->specialization) == 'GE_CF_3A' ? 'selected' : '' }}>Gestion des Entreprises option Comptabilité et Finance (3ème année)</option>
                                <option value="GE_RH_2A" {{ old('specialization', $candidate->specialization) == 'GE_RH_2A' ? 'selected' : '' }}>Gestion des Entreprises option Ressources Humaines (2ème année)</option>
                                <option value="GE_RH_3A" {{ old('specialization', $candidate->specialization) == 'GE_RH_3A' ? 'selected' : '' }}>Gestion des Entreprises option Ressources Humaines (3ème année)</option>
                                <option value="GE_OM_2A" {{ old('specialization', $candidate->specialization) == 'GE_OM_2A' ? 'selected' : '' }}>Gestion des Entreprises option Office Manager (2ème année)</option>
                                <option value="GE_OM_3A" {{ old('specialization', $candidate->specialization) == 'GE_OM_3A' ? 'selected' : '' }}>Gestion des Entreprises option Office Manager (3ème année)</option>
                            </optgroup>
                            
                            <!-- Infrastructure Digitale -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Infrastructure Digitale">
                                <option value="ID_1A" {{ old('specialization', $candidate->specialization) == 'ID_1A' ? 'selected' : '' }}>1ere Année: Infrastructure Digitale</option>
                                <option value="ID_RS_2A" {{ old('specialization', $candidate->specialization) == 'ID_RS_2A' ? 'selected' : '' }}>2eme Année: Option Réseaux et systèmes</option>
                                <option value="ID_CC_2A" {{ old('specialization', $candidate->specialization) == 'ID_CC_2A' ? 'selected' : '' }}>2eme Année: Option Cloud Computing</option>
                                <option value="ID_CS_2A" {{ old('specialization', $candidate->specialization) == 'ID_CS_2A' ? 'selected' : '' }}>2eme Année: Option Cyber security</option>
                                <option value="ID_IOT_2A" {{ old('specialization', $candidate->specialization) == 'ID_IOT_2A' ? 'selected' : '' }}>2eme Année: Option IOT</option>
                            </optgroup>
                            
                            <!-- Développement Digital -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Développement Digital">
                                <option value="DD_1A" {{ old('specialization', $candidate->specialization) == 'DD_1A' ? 'selected' : '' }}>1ere Année: Développement Digital</option>
                                <option value="DD_AM_2A" {{ old('specialization', $candidate->specialization) == 'DD_AM_2A' ? 'selected' : '' }}>2eme Année: Développement Digital option Applications Mobiles</option>
                                <option value="DD_WFS_2A" {{ old('specialization', $candidate->specialization) == 'DD_WFS_2A' ? 'selected' : '' }}>2eme Année: Développement Digital option Web Full Stack</option>
                            </optgroup>
                            
                            <!-- Génie Electrique -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Génie Electrique">
                                <option value="GE_1A" {{ old('specialization', $candidate->specialization) == 'GE_1A' ? 'selected' : '' }}>Génie Electrique 1ére année</option>
                            </optgroup>
                            
                            <!-- Digital design -->
                            <option value="DD" {{ old('specialization', $candidate->specialization) == 'DD' ? 'selected' : '' }}>Digital design</option>
                            
                            <!-- Techniques Habillement Industrialisation -->
                            <option value="THI" {{ old('specialization', $candidate->specialization) == 'THI' ? 'selected' : '' }}>Techniques Habillement Industrialisation</option>
                            
                            <!-- Génie civil -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Génie civil">
                                <option value="GC_1A" {{ old('specialization', $candidate->specialization) == 'GC_1A' ? 'selected' : '' }}>1ére année: Génie Civil</option>
                                <option value="GC_B_2A" {{ old('specialization', $candidate->specialization) == 'GC_B_2A' ? 'selected' : '' }}>2éme année: Génie Civil option Bâtiments</option>
                            </optgroup>
                            
                            <!-- Other specializations -->
                            <option value="TRI" {{ old('specialization', $candidate->specialization) == 'TRI' ? 'selected' : '' }}>TRI: Techniques des Réseaux Informatiques</option>
                            <option value="TDI" {{ old('specialization', $candidate->specialization) == 'TDI' ? 'selected' : '' }}>TDI: Techniques de Développement Informatique</option>
                            <option value="TSGE" {{ old('specialization', $candidate->specialization) == 'TSGE' ? 'selected' : '' }}>TSGE: Technicien Spécialisé en Gestion des Entreprises</option>
                            <option value="TSC" {{ old('specialization', $candidate->specialization) == 'TSC' ? 'selected' : '' }}>TSC: Technicien Spécialisé en Commerce</option>
                            <option value="ESA" {{ old('specialization', $candidate->specialization) == 'ESA' ? 'selected' : '' }}>ESA: Electromécanique des Systèmes Automatisées</option>
                        </optgroup>
                        
                        <!-- NTIC -->
                        <optgroup label="NTIC">
                            <option value="TRI_NTIC" {{ old('specialization', $candidate->specialization) == 'TRI_NTIC' ? 'selected' : '' }}>TRI: Techniques des Réseaux Informatiques</option>
                            <option value="TDI_NTIC" {{ old('specialization', $candidate->specialization) == 'TDI_NTIC' ? 'selected' : '' }}>TDI: Techniques de Développement Informatiques</option>
                            <option value="TDM" {{ old('specialization', $candidate->specialization) == 'TDM' ? 'selected' : '' }}>TDM: Techniques de Développement Multimédia</option>
                        </optgroup>
                        
                        <!-- AGC -->
                        <optgroup label="AGC: Administration Gestion et Commerce">
                            <option value="TSGE_AGC" {{ old('specialization', $candidate->specialization) == 'TSGE_AGC' ? 'selected' : '' }}>TSGE: Technicien Spécialisé en Gestion des Entreprises</option>
                            <option value="TSFC" {{ old('specialization', $candidate->specialization) == 'TSFC' ? 'selected' : '' }}>TSFC: Technicien Spécialisé en Finance et Comptabilité</option>
                            <option value="TSC_AGC" {{ old('specialization', $candidate->specialization) == 'TSC_AGC' ? 'selected' : '' }}>TSC: Technicien Spécialisé en Commerce</option>
                            <option value="TSD" {{ old('specialization', $candidate->specialization) == 'TSD' ? 'selected' : '' }}>TSD: Technique de Secrétariat de Direction</option>
                        </optgroup>
                        
                        <!-- BTP -->
                        <optgroup label="BTP">
                            <option value="TSGO" {{ old('specialization', $candidate->specialization) == 'TSGO' ? 'selected' : '' }}>TSGO: Technicien spécialisé Gros Œuvres</option>
                        </optgroup>
                        
                        <!-- Construction Métallique -->
                        <optgroup label="Construction Métallique">
                            <option value="TSBECM" {{ old('specialization', $candidate->specialization) == 'TSBECM' ? 'selected' : '' }}>TSBECM: Technicien Spécialisé Bureau d'Etude en Construction Métallique</option>
                        </optgroup>
                        
                        <!-- TH -->
                        <optgroup label="TH">
                            <option value="THI_TH" {{ old('specialization', $candidate->specialization) == 'THI_TH' ? 'selected' : '' }}>Techniques Habillement Industrialisation</option>
                        </optgroup>
                        
                        <!-- Niveau Technicien -->
                        <optgroup label="Niveau Technicien">
                            <!-- Assistant Administratif -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Assistant Administratif">
                                <option value="TMSIR" {{ old('specialization', $candidate->specialization) == 'TMSIR' ? 'selected' : '' }}>TMSIR: Technicien en Maintenance et Support Informatique et Réseaux</option>
                                <option value="ATV" {{ old('specialization', $candidate->specialization) == 'ATV' ? 'selected' : '' }}>ATV: Agent Technique de Vente</option>
                                <option value="TCE" {{ old('specialization', $candidate->specialization) == 'TCE' ? 'selected' : '' }}>TCE: Technicien Comptable d'Entreprises</option>
                                <option value="EMI" {{ old('specialization', $candidate->specialization) == 'EMI' ? 'selected' : '' }}>EMI: Technicien en Electricité de Maintenance Industrielle</option>
                            </optgroup>
                        </optgroup>
                    </select>
                    @error('specialization')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Selection Criteria Section -->
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Selection Criteria</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="criteria-container">
                    <!-- Criteria groups will be dynamically added here -->
                </div>
                <button type="button" id="add-more-criteria" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Another Criteria
                </button>
            </div>

            <!-- Parent or Guardian Info Section -->
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Parent or Guardian Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="guardian_first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="guardian_first_name" id="guardian_first_name" value="{{ old('guardian_first_name', $candidate->guardian_first_name) }}" placeholder="Enter Parent's or Guardian's first name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @error('guardian_first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="guardian_last_name" class="block text-sm font-medium text-gray-700 mb-1">Family Name</label>
                        <input type="text" name="guardian_last_name" id="guardian_last_name" value="{{ old('guardian_last_name', $candidate->guardian_last_name) }}" placeholder="Enter Parent's or Guardian's Family name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @error('guardian_last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="guardian_dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="guardian_dob" id="guardian_dob" value="{{ old('guardian_dob', $candidate->guardian_dob ? $candidate->guardian_dob->format('Y-m-d') : '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @error('guardian_dob')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="guardian_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" name="guardian_phone" id="guardian_phone" value="{{ old('guardian_phone', $candidate->guardian_phone) }}" placeholder="Enter parent or guardian's phone number" pattern="[0-9+]*" inputmode="tel" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" oninput="this.value = this.value.replace(/[^0-9+]/g, '');">
                        <p class="text-xs text-gray-500 mt-1">You can only enter numbers</p>
                        @error('guardian_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>
                </div>

                <!-- Supporting Documents Section -->
                <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Supporting Documents</h3>
                <div class="border border-gray-300 rounded-lg p-6 bg-gray-50">
                    <div class="mb-4">
                        <label for="supporting_documents" class="block text-sm font-medium text-gray-700 mb-2">Upload Documents <span class="text-xs text-gray-500">(Maximum 5 files)</span></label>
                        <div id="document-drop-area" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors duration-200">
                            <div id="document-upload-prompt" class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">PDF, PNG, JPG, DOCX, XLS, ZIP (MAX. 10MB)</p>
                            </div>
                            <div id="document-preview-container" class="hidden w-full px-4 py-2">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-sm font-medium text-gray-700">Selected Files</h4>
                                    <span id="file-count" class="text-xs text-gray-500">0/5 files</span>
                                </div>
                                <div id="document-previews" class="grid grid-cols-1 gap-2 max-h-60 overflow-y-auto"></div>
                            </div>
                            <input id="supporting_documents" name="supporting_documents[]" type="file" class="hidden" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.zip" />
                        </div>
                        @error('supporting_documents')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Required Documents:</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>CIN Copy</li>
                            <li>Proof of Residence</li>
                            <li>Income Proof</li>
                        </ul>
                    </div>

                    @if(isset($candidate->documents) && count($candidate->documents) > 0)
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Current Documents:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($candidate->documents as $document)
                            <div class="flex items-center p-3 bg-white rounded-md border border-gray-200">
                                <div class="flex-shrink-0 mr-3">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $document->name }}</p>
                                    <p class="text-xs text-gray-500">Uploaded: {{ $document->created_at->format('M d, Y') }}</p>
                                </div>
                            <div class="flex items-center">
                                <a href="{{ route('documents.download', $document->id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-3">Download</a>
                                <input type="checkbox" name="delete_documents[]" value="{{ $document->id }}" class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <label class="sr-only">Delete {{ $document->name }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Declaration of Truthfulness -->            
            <div class="mt-8 mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-sm">
                <div class="flex items-start">
                    <div class="flex items-center h-5 mt-1">
                        <input id="declaration" name="declaration" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('declaration', $candidate->declaration) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3">
                        <label for="declaration" class="text-sm font-medium text-gray-700">
                            Declaration of Truthfulness (Optional)
                        </label>
                        <p class="text-gray-600 text-sm mt-1">I hereby declare that all information provided is true and correct to the best of my knowledge.</p>
                        @error('declaration')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <a href="{{ route('candidates.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                    Update Candidate
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Pass the candidate's existing criteria to the dynamic criteria handler
    window.candidateExistingCriteria = @json($candidate->criteria->map(function($criteria) {
        return [
            'category' => $criteria->category,
            'criteria_id' => $criteria->id,
            'score' => $criteria->pivot->score ?? null,
            'note' => $criteria->pivot->note ?? null
        ];
    })->values()->toArray() ?? [];

    // Pass the candidate's existing documents to the file upload handler
    window.existingDocuments = @json($candidate->documents->map(function($document) {
        return [
            'id' => $document->id,
            'name' => $document->original_filename,
            'url' => route('documents.download', $document->id),
            'type' => $document->file_type,
            'size' => $document->file_size
        ];
    })->values()->toArray());
</script>

<!-- Include the criteria handler JavaScript -->
@vite(['resources/js/candidates/dynamic-criteria.js'])

<!-- JavaScript for file upload preview -->
<script src="{{ asset('js/candidates/file-upload.js') }}"></script>

<!-- Form submission handling -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('candidateForm');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Form submission started');
            
            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton ? submitButton.innerHTML : '';
            
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
            }
            
            try {
                // Create form data
                const formData = new FormData(form);
                
                // Log form data for debugging
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                
                // Submit the form using fetch
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                console.log('Response status:', response.status);
                const data = await response.json().catch(() => ({}));
                
                if (!response.ok) {
                    throw data;
                }
                
                // Redirect on success
                window.location.href = '{{ route("candidates.index") }}';
                
            } catch (error) {
                console.error('Error details:', error);
                
                // Re-enable the submit button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
                
                // Handle validation errors
                if (error.errors) {
                    // Clear previous error messages
                    document.querySelectorAll('.validation-error').forEach(el => el.remove());
                    
                    // Show validation errors next to the fields
                    for (const [field, messages] of Object.entries(error.errors)) {
                        const input = form.querySelector(`[name^="${field}"]`);
                        if (input) {
                            input.classList.add('border-red-500');
                            
                            // Create error message element
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'validation-error text-red-500 text-xs mt-1';
                            errorDiv.textContent = Array.isArray(messages) ? messages[0] : messages;
                            
                            // Insert after the input
                            input.parentNode.insertBefore(errorDiv, input.nextSibling);
                            
                            // Remove error on input
                            input.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                const errorMsg = this.nextElementSibling;
                                if (errorMsg && errorMsg.classList.contains('validation-error')) {
                                    errorMsg.remove();
                                }
                            });
                        }
                    }
                    
                    // Show alert with all errors
                    let errorMessages = [];
                    for (const [field, messages] of Object.entries(error.errors)) {
                        const fieldLabel = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        const message = Array.isArray(messages) ? messages[0] : messages;
                        errorMessages.push(`${fieldLabel}: ${message}`);
                    }
                    alert('Please fix the following errors:\n\n' + errorMessages.join('\n'));
                } else {
                    // For non-validation errors
                    const errorMessage = error.message || 'An unknown error occurred';
                    alert(`Error: ${errorMessage}`);
                }
            }
            
            return false;
        });
    }
});
</script>

@push('scripts')

<script>
    $(document).ready(function() {
        // Initialize Select2 for specialization dropdown
        $('.select2-specialization').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Search or select a specialization',
            allowClear: true,
            dropdownParent: $('#specialization').parent(),
            minimumResultsForSearch: 1
        });

        let criteriaCount = 0; // Initialize count for new groups
        // Use candidate.criteria to get both category and id for pre-population
        // Removed: const existingCriteriaData = @json($candidate->criteria->map(function($item) {
        // Removed:     return ['category' => $item->category, 'id' => $item->id];
        // Removed: })->values()->toArray());

        function generateCriteriaGroupHtml(index, selectedCategory = '', selectedType = '') {
            const criteriaCategories = [
                { value: '', text: 'Select a category' },
                { value: 'geographical', text: 'Geographical' },
                { value: 'social', text: 'Social' },
                { value: 'academic', text: 'Academic' },
                { value: 'physical', text: 'Physical' },
                { value: 'family', text: 'Family' }
            ];

            let categoryOptions = '';
            criteriaCategories.forEach(cat => {
                categoryOptions += `<option value="${cat.value}" ${selectedCategory === cat.value ? 'selected' : ''}>${cat.text}</option>`;
            });

            return `
                <div class="criteria-group bg-gray-50 p-4 rounded-md shadow-sm mb-4 border border-gray-200" data-index="${index}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-800">Criterion #${index + 1}</h4>
                        <button type="button" class="remove-criteria-group text-red-600 hover:text-red-900 focus:outline-none" title="Remove this criterion">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="criteria_category_${index}" class="block text-sm font-medium text-gray-700 mb-1">Criteria Category</label>
                            <select name="criteria[${index}][category]" id="criteria_category_${index}" class="criteria-category w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                ${categoryOptions}
                            </select>
                        </div>
                        <div>
                            <label for="criteria_type_${index}" class="block text-sm font-medium text-gray-700 mb-1">Criterion Type</label>
                            <select name="criteria[${index}][type]" id="criteria_type_${index}" class="criteria-type w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                <option value="">Select a criterion type</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
        }

        function attachCategoryChangeListener($categorySelect) {
            $categorySelect.on('change', function() {
                const selectedCategory = $(this).val();
                const $criteriaTypeSelect = $(this).closest('.criteria-group').find('.criteria-type');
                // Get previously selected criterion for this specific group
                const selectedCriterionId = $criteriaTypeSelect.data('selected'); 

                $criteriaTypeSelect.empty().append('<option value="">Select a criterion type</option>');

                if (selectedCategory) {
                    $.ajax({
                        url: '/api/criteria',
                        method: 'GET',
                        data: { category: selectedCategory },
                        success: function(response) {
                            if (response.success && response.data.length > 0) {
                                response.data.forEach(function(criterion) {
                                    const isSelected = (selectedCriterionId && selectedCriterionId == criterion.id) ? 'selected' : '';
                                    $criteriaTypeSelect.append(`<option value="${criterion.id}" ${isSelected}>${criterion.text}</option>`);
                                });
                            } else {
                                $criteriaTypeSelect.append('<option value="">No criteria found for this category</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching criteria:', error);
                            $criteriaTypeSelect.append('<option value="">Error loading criteria</option>');
                        }
                    });
                }
            });
        }

        // Pre-populate existing criteria
        if (existingCriteriaData.length > 0) {
            let initialIndex = 0;
            existingCriteriaData.forEach(function(criterionData) {
                const newCriteriaGroup = $(generateCriteriaGroupHtml(initialIndex, criterionData.category));
                $('#criteria-container').append(newCriteriaGroup);
                
                const $criteriaTypeSelect = newCriteriaGroup.find('.criteria-type');
                $criteriaTypeSelect.data('selected', criterionData.id);

                attachCategoryChangeListener(newCriteriaGroup.find('.criteria-category'));
                newCriteriaGroup.find('.criteria-category').trigger('change'); // Trigger change to load types
                initialIndex++;
            });
            criteriaCount = initialIndex;
        } else {
            // If no existing criteria, add one empty group
            const initialGroup = $(generateCriteriaGroupHtml(0));
            $('#criteria-container').append(initialGroup);
            attachCategoryChangeListener(initialGroup.find('.criteria-category'));
            criteriaCount = 1; // Set count to 1 for the initial group
        }

        // Handle adding more criteria groups
        $('#add-more-criteria').on('click', function() {
            const newCriteriaGroup = $(generateCriteriaGroupHtml(criteriaCount));
            $('#criteria-container').append(newCriteriaGroup);
            attachCategoryChangeListener(newCriteriaGroup.find('.criteria-category'));
            criteriaCount++;
        });

        // Handle removing criteria groups
        $('#criteria-container').on('click', '.remove-criteria-group', function() {
            $(this).closest('.criteria-group').remove();
            // Re-index remaining criteria groups to maintain sequential array keys
            $('.criteria-group').each(function(index) {
                $(this).find('h4').text(`Criterion #${index + 1}`);
                $(this).find('.criteria-category').attr({'name': `criteria[${index}][category]`, 'id': `criteria_category_${index}`});
                $(this).find('.criteria-type').attr({'name': `criteria[${index}][type]`, 'id': `criteria_type_${index}`});
            });
            criteriaCount--;
        });
    });
</script>
@endpush
@endsection
