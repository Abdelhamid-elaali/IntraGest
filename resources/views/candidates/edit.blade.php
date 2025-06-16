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
                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Supporting Documents</h3>
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
                                    <div id="document-previews" class="grid grid-cols-1 gap-2 max-h-60 overflow-y-auto">
                                        <!-- Existing documents will be populated by JavaScript -->
                                    </div>
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
                    </div>
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

@push('scripts')
@vite(['resources/views/candidates/create-file-upload.js'])
@endpush

<script>
// Pass the candidate's existing criteria to the dynamic criteria handler
@php
    $criteriaData = $candidate->criteria->map(function($criteria) {
        return [
            'category' => $criteria->category,
            'criteria_id' => (string) $criteria->id, // Ensure it's a string for comparison
            'score' => $criteria->pivot->score ?? null,
            'note' => $criteria->pivot->note ?? null
        ];
    })->values()->all();

    $documentsData = $candidate->documents->map(function($document) {
        return [
            'id' => $document->id,
            'original_filename' => $document->original_filename,
            'url' => route('documents.download', $document->id),
            'file_type' => $document->file_type,
            'file_size' => $document->file_size
        ];
    })->values()->all();
@endphp

// Make data available to the file upload script
window.candidateExistingCriteria = {!! json_encode($criteriaData) !!};
window.existingDocuments = {!! json_encode($documentsData) !!};

// Global function to handle removing existing documents
window.removeExistingDocument = function(documentId) {
    if (confirm('Are you sure you want to remove this document?')) {
        // Create a hidden input to track removed documents
        const removedInput = document.createElement('input');
        removedInput.type = 'hidden';
        removedInput.name = 'remove_documents[]';
        removedInput.value = documentId;
        document.getElementById('candidateForm').appendChild(removedInput);
        
        // Remove the document preview
        const preview = document.querySelector(`[data-document-id="${documentId}"]`);
        if (preview) {
            preview.remove();
        }
        
        // Show success message
        const alert = document.createElement('div');
        alert.className = 'mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-md';
        alert.textContent = 'Document removed successfully';
        document.querySelector('form').insertBefore(alert, document.querySelector('form').firstChild);
        
        // Remove the alert after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
        
        // Update file count
            updateFileCount();
        
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded relative';
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <span class="block sm:inline">Document will be removed when you save the form.</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        `;
        
        // Insert the alert after the form
        const form = document.getElementById('candidateForm');
        form.parentNode.insertBefore(alertDiv, form.nextSibling);
        
        // Auto-remove the alert after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
};

    // Function to update the file count display
    function updateFileCount() {
        const existingPreviews = document.querySelectorAll('.document-preview-item[data-document-id]');
        const newPreviews = document.querySelectorAll('.document-preview-item[data-index]');
        const totalCount = existingPreviews.length + newPreviews.length;
        
        const fileCountEl = document.getElementById('file-count');
        if (fileCountEl) {
            fileCountEl.textContent = `${totalCount}/5 files`;
            fileCountEl.className = totalCount === 5 ? 
                'text-xs font-medium text-amber-600' : 'text-xs text-gray-500';
        }
    }
    
    // Initialize file count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateFileCount();
    });
</script>

<!-- Include the criteria handler JavaScript -->
@vite(['resources/js/candidates/dynamic-criteria.js'])

@push('scripts')
<script src="{{ asset('js/candidates/file-upload.js') }}"></script>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('candidateForm');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton ? submitButton.innerHTML : '';
            
            // Show loading state on submit button
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Updating...
                `;
            }
            
            // Show loading state
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;
            }
            
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json().catch(() => ({}));
                
                if (!response.ok) {
                    throw data;
                }
                
                // Redirect on success
                window.location.href = data.redirect || '{{ route("candidates.index") }}';
                
            } catch (error) {
                console.error('Error details:', error);
                
                // Re-enable the submit button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
                
                // Handle validation errors
                if (error.errors) {
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
            placeholder: 'Select a specialization',
            allowClear: true,
            dropdownParent: $('#candidateForm')
        });

        // Document upload handling
        const dropArea = document.getElementById('document-drop-area');
        const fileInput = document.getElementById('supporting_documents');
        const previewContainer = document.getElementById('document-preview-container');
        const previews = document.getElementById('document-previews');
        const uploadPrompt = document.getElementById('document-upload-prompt');
        const fileCount = document.getElementById('file-count');
        let files = [];
        let existingFiles = [];

        // Load existing files from the preview container
        document.querySelectorAll('.document-preview-item').forEach(item => {
            const docId = item.getAttribute('data-document-id');
            if (docId) {
                existingFiles.push(docId);
            }
        });

        // Show/hide upload prompt based on files
        function updateUI() {
            const totalFiles = files.length + existingFiles.length;
            if (totalFiles > 0) {
                uploadPrompt.classList.add('hidden');
                previewContainer.classList.remove('hidden');
            } else {
                uploadPrompt.classList.remove('hidden');
                previewContainer.classList.add('hidden');
            }
            fileCount.textContent = `${totalFiles}/5 files`;
        }

        // Handle file selection
        fileInput.addEventListener('change', (e) => {
            const newFiles = Array.from(e.target.files);
            
            // Check total files won't exceed limit
            if (files.length + existingFiles.length + newFiles.length > 5) {
                alert('You can upload a maximum of 5 files in total.');
                return;
            }
            
            // Add new files
            newFiles.forEach(file => {
                if (file.size > 10 * 1024 * 1024) { // 10MB limit
                    alert(`File ${file.name} is too large. Maximum size is 10MB.`);
                    return;
                }
                
                files.push(file);
                
                const filePreview = document.createElement('div');
                filePreview.className = 'document-preview-item flex items-center justify-between p-2 bg-white border rounded-md';
                filePreview.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-700 truncate max-w-xs">${file.name}</span>
                    </div>
                    <button type="button" class="text-red-600 hover:text-red-800 text-xs font-medium remove-file">
                        Remove
                    </button>
                `;
                
                previews.appendChild(filePreview);
                
                // Add remove event listener
                filePreview.querySelector('.remove-file').addEventListener('click', () => {
                    const index = files.findIndex(f => f.name === file.name);
                    if (index > -1) {
                        files.splice(index, 1);
                    }
                    filePreview.remove();
                    updateUI();
                });
            });
            
            updateUI();
            fileInput.value = ''; // Reset file input to allow selecting the same file again
        });

        // Handle remove document button clicks
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-document')) {
                e.preventDefault();
                const docId = e.target.getAttribute('data-document-id');
                const docItem = e.target.closest('.document-preview-item');
                // This is now handled by the global removeExistingDocument function
                
                if (confirm('Are you sure you want to remove this document?')) {
                    // Remove from existing files array
                    existingFiles = existingFiles.filter(id => id !== docId);
                    
                    // Create a hidden input to track removed documents
                    const removedInput = document.createElement('input');
                    removedInput.type = 'hidden';
                    removedInput.name = 'removed_documents[]';
                    removedInput.value = docId;
                    document.getElementById('candidateForm').appendChild(removedInput);
                    
                    // Remove from DOM
                    docItem.remove();
                    updateUI();
                }
            }
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight() {
            dropArea.classList.remove('border-blue-500', 'bg-blue-50');
        }

        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const droppedFiles = dt.files;
            
            if (droppedFiles.length > 0) {
                const fileList = new DataTransfer();
                Array.from(droppedFiles).forEach(file => fileList.items.add(file));
                fileInput.files = fileList.files;
                
                // Trigger change event
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        }

        // Initial UI update
        updateUI();
    });
</script>
@endpush
@endsection
