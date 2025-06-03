@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Add New Candidate</h1>
        <p class="text-gray-600">Enter the details of the new candidate below.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('candidates.store') }}" class="space-y-6" enctype="multipart/form-data" id="candidateForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="Enter candidate's first name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Family Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="Enter candidate's family name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cin" class="block text-sm font-medium text-gray-700 mb-1">CIN ID</label>
                    <input type="text" name="cin" id="cin" value="{{ old('cin') }}" placeholder="Enter candidate's CIN ID" pattern="[A-Za-z0-9]+" title="Only letters and numbers allowed" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    <p class="text-xs text-gray-500 mt-1">Only letters and numbers are allowed, no symbols.</p>
                    @error('cin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter candidate's email address" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Enter candidate's phone number" pattern="[0-9+]*" inputmode="tel" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" oninput="this.value = this.value.replace(/[^0-9+]/g, '');" required>
                    <p class="text-xs text-gray-500 mt-1">Only numbers and the plus (+) symbol are allowed</p>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                    <input type="text" name="nationality" id="nationality" value="{{ old('nationality', 'Moroccan') }}" placeholder="Enter candidate's nationality" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('nationality')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="distance" class="block text-sm font-medium text-gray-700 mb-1">Distance from Institute (km)</label>
                    <input type="number" name="distance" id="distance" value="{{ old('distance') }}" placeholder="Enter distance in kilometers" min="0" step="0.1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('distance')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <div class="mt-2 flex items-center space-x-6">
                        <div class="flex items-center">
                            <input id="gender-male" name="gender" type="radio" value="male" {{ old('gender', 'male') == 'male' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                            <label for="gender-male" class="ml-2 block text-sm text-gray-700">Male</label>
                        </div>
                        <div class="flex items-center">
                            <input id="gender-female" name="gender" type="radio" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="gender-female" class="ml-2 block text-sm text-gray-700">Female</label>
                        </div>
                    </div>
                    @error('gender')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address / Place of Residence</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Enter candidate's full address and place of residence" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    <p class="text-xs text-gray-500 mt-1">Include both address and place of residence information</p>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" placeholder="Enter candidate's city" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Social Criteria -->
                <div>
                    <label for="income_level" class="block text-sm font-medium text-gray-700 mb-1">Income Level</label>
                    <select name="income_level" id="income_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Income Level</option>
                        <option value="low" {{ old('income_level') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('income_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('income_level') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('income_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Has Disability Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Has Disability</label>
                    <div class="mt-1 space-y-2">
                        <div class="flex items-center">
                            <input id="has-disability-yes" name="has_disability" type="radio" value="1" {{ old('has_disability') == '1' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="has-disability-yes" class="ml-2 block text-sm text-gray-700">Yes</label>
                        </div>
                        <div class="flex items-center">
                            <input id="has-disability-no" name="has_disability" type="radio" value="0" {{ old('has_disability', '0') == '0' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                            <label for="has-disability-no" class="ml-2 block text-sm text-gray-700">No</label>
                        </div>
                    </div>
                    @error('has_disability')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Criteria -->
                <div>
                    <label for="training_level" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <select name="training_level" id="training_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required onchange="updateAcademicYear()">
                        <option value="">Select Academic Year</option>
                        <option value="first_year" {{ old('training_level') == 'first_year' ? 'selected' : '' }}>First Year</option>
                        <option value="second_year" {{ old('training_level') == 'second_year' ? 'selected' : '' }}>Second Year</option>
                        <option value="third_year" {{ old('training_level') == 'third_year' ? 'selected' : '' }}>Third Year</option>
                    </select>
                    @error('training_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <!-- Hidden field for academic_year -->
                    <input type="hidden" name="academic_year" id="academic_year" value="{{ old('academic_year') }}">
                </div>

                <!-- Specialization field removed to avoid duplication - using the detailed specialization field below -->

                <div>
                    <label for="educational_level" class="block text-sm font-medium text-gray-700 mb-1">Educational Level</label>
                    <select name="educational_level" id="educational_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Educational Level</option>
                        <option value="specialized_technician" {{ old('educational_level') == 'specialized_technician' ? 'selected' : '' }}>Specialized Technician</option>
                        <option value="technical" {{ old('educational_level') == 'technical' ? 'selected' : '' }}>Technical</option>
                        <option value="qualification" {{ old('educational_level') == 'qualification' ? 'selected' : '' }}>Qualification</option>
                    </select>
                    @error('educational_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                    <select name="specialization" id="specialization" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select a specialization</option>
                        
                        <!-- Technicien Spécialisé -->
                        <optgroup label="Technicien Spécialisé">
                            <!-- Gestion des entreprise -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Gestion des entreprise">
                                <option value="GE_1A" {{ old('specialization') == 'GE_1A' ? 'selected' : '' }}>Gestion des Entreprises 1ère année</option>
                                <option value="GE_CM_2A" {{ old('specialization') == 'GE_CM_2A' ? 'selected' : '' }}>Gestion des Entreprises option Commerce et Marketing (2ème année)</option>
                                <option value="GE_CM_3A" {{ old('specialization') == 'GE_CM_3A' ? 'selected' : '' }}>Gestion des Entreprises option Commerce et Marketing (3ème année)</option>
                                <option value="GE_CF_2A" {{ old('specialization') == 'GE_CF_2A' ? 'selected' : '' }}>Gestion des Entreprises option Comptabilité et Finance (2ème année)</option>
                                <option value="GE_CF_3A" {{ old('specialization') == 'GE_CF_3A' ? 'selected' : '' }}>Gestion des Entreprises option Comptabilité et Finance (3ème année)</option>
                                <option value="GE_RH_2A" {{ old('specialization') == 'GE_RH_2A' ? 'selected' : '' }}>Gestion des Entreprises option Ressources Humaines (2ème année)</option>
                                <option value="GE_RH_3A" {{ old('specialization') == 'GE_RH_3A' ? 'selected' : '' }}>Gestion des Entreprises option Ressources Humaines (3ème année)</option>
                                <option value="GE_OM_2A" {{ old('specialization') == 'GE_OM_2A' ? 'selected' : '' }}>Gestion des Entreprises option Office Manager (2ème année)</option>
                                <option value="GE_OM_3A" {{ old('specialization') == 'GE_OM_3A' ? 'selected' : '' }}>Gestion des Entreprises option Office Manager (3ème année)</option>
                            </optgroup>
                            
                            <!-- Infrastructure Digitale -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Infrastructure Digitale">
                                <option value="ID_1A" {{ old('specialization') == 'ID_1A' ? 'selected' : '' }}>1ere Année: Infrastructure Digitale</option>
                                <option value="ID_RS_2A" {{ old('specialization') == 'ID_RS_2A' ? 'selected' : '' }}>2eme Année: Option Réseaux et systèmes</option>
                                <option value="ID_CC_2A" {{ old('specialization') == 'ID_CC_2A' ? 'selected' : '' }}>2eme Année: Option Cloud Computing</option>
                                <option value="ID_CS_2A" {{ old('specialization') == 'ID_CS_2A' ? 'selected' : '' }}>2eme Année: Option Cyber sécurité</option>
                                <option value="ID_IOT_2A" {{ old('specialization') == 'ID_IOT_2A' ? 'selected' : '' }}>2eme Année: Option IOT</option>
                            </optgroup>
                            
                            <!-- Développement Digital -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Développement Digital">
                                <option value="DD_1A" {{ old('specialization') == 'DD_1A' ? 'selected' : '' }}>1ere Année: Développement Digital</option>
                                <option value="DD_AM_2A" {{ old('specialization') == 'DD_AM_2A' ? 'selected' : '' }}>2eme Année: Développement Digital option Applications Mobiles</option>
                                <option value="DD_WFS_2A" {{ old('specialization') == 'DD_WFS_2A' ? 'selected' : '' }}>2eme Année: Développement Digital option Web Full Stack</option>
                            </optgroup>
                            
                            <!-- Génie Electrique -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Génie Electrique">
                                <option value="GE_1A" {{ old('specialization') == 'GE_1A' ? 'selected' : '' }}>Génie Electrique 1ére année</option>
                            </optgroup>
                            
                            <!-- Digital design -->
                            <option value="DD" {{ old('specialization') == 'DD' ? 'selected' : '' }}>Digital design</option>
                            
                            <!-- Techniques Habillement Industrialisation -->
                            <option value="THI" {{ old('specialization') == 'THI' ? 'selected' : '' }}>Techniques Habillement Industrialisation</option>
                            
                            <!-- Génie civil -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Génie civil">
                                <option value="GC_1A" {{ old('specialization') == 'GC_1A' ? 'selected' : '' }}>1ére année: Génie Civil</option>
                                <option value="GC_B_2A" {{ old('specialization') == 'GC_B_2A' ? 'selected' : '' }}>2éme année: Génie Civil option Bâtiments</option>
                            </optgroup>
                            
                            <!-- Other specializations -->
                            <option value="TRI" {{ old('specialization') == 'TRI' ? 'selected' : '' }}>TRI: Techniques des Réseaux Informatiques</option>
                            <option value="TDI" {{ old('specialization') == 'TDI' ? 'selected' : '' }}>TDI: Techniques de Développement Informatique</option>
                            <option value="TSGE" {{ old('specialization') == 'TSGE' ? 'selected' : '' }}>TSGE: Technicien Spécialisé en Gestion des Entreprises</option>
                            <option value="TSC" {{ old('specialization') == 'TSC' ? 'selected' : '' }}>TSC: Technicien Spécialisé en Commerce</option>
                            <option value="ESA" {{ old('specialization') == 'ESA' ? 'selected' : '' }}>ESA: Electromécanique des Systèmes Automatisées</option>
                        </optgroup>
                        
                        <!-- NTIC -->
                        <optgroup label="NTIC">
                            <option value="TRI_NTIC" {{ old('specialization') == 'TRI_NTIC' ? 'selected' : '' }}>TRI: Techniques des Réseaux Informatiques</option>
                            <option value="TDI_NTIC" {{ old('specialization') == 'TDI_NTIC' ? 'selected' : '' }}>TDI: Techniques de Développement Informatiques</option>
                            <option value="TDM" {{ old('specialization') == 'TDM' ? 'selected' : '' }}>TDM: Techniques de Développement Multimédia</option>
                        </optgroup>
                        
                        <!-- AGC -->
                        <optgroup label="AGC: Administration Gestion et Commerce">
                            <option value="TSGE_AGC" {{ old('specialization') == 'TSGE_AGC' ? 'selected' : '' }}>TSGE: Technicien Spécialisé en Gestion des Entreprises</option>
                            <option value="TSFC" {{ old('specialization') == 'TSFC' ? 'selected' : '' }}>TSFC: Technicien Spécialisé en Finance et Comptabilité</option>
                            <option value="TSC_AGC" {{ old('specialization') == 'TSC_AGC' ? 'selected' : '' }}>TSC: Technicien Spécialisé en Commerce</option>
                            <option value="TSD" {{ old('specialization') == 'TSD' ? 'selected' : '' }}>TSD: Technique de Secrétariat de Direction</option>
                        </optgroup>
                        
                        <!-- BTP -->
                        <optgroup label="BTP">
                            <option value="TSGO" {{ old('specialization') == 'TSGO' ? 'selected' : '' }}>TSGO: Technicien spécialisé Gros Œuvres</option>
                        </optgroup>
                        
                        <!-- Construction Métallique -->
                        <optgroup label="Construction Métallique">
                            <option value="TSBECM" {{ old('specialization') == 'TSBECM' ? 'selected' : '' }}>TSBECM: Technicien Spécialisé Bureau d'Etude en Construction Métallique</option>
                        </optgroup>
                        
                        <!-- TH -->
                        <optgroup label="TH">
                            <option value="THI_TH" {{ old('specialization') == 'THI_TH' ? 'selected' : '' }}>Techniques Habillement Industrialisation</option>
                        </optgroup>
                        
                        <!-- Niveau Technicien -->
                        <optgroup label="Niveau Technicien">
                            <!-- Assistant Administratif -->
                            <optgroup label="&nbsp;&nbsp;&nbsp;Assistant Administratif">
                                <option value="TMSIR" {{ old('specialization') == 'TMSIR' ? 'selected' : '' }}>TMSIR: Technicien en Maintenance et Support Informatique et Réseaux</option>
                                <option value="ATV" {{ old('specialization') == 'ATV' ? 'selected' : '' }}>ATV: Agent Technique de Vente</option>
                                <option value="TCE" {{ old('specialization') == 'TCE' ? 'selected' : '' }}>TCE: Technicien Comptable d'Entreprises</option>
                                <option value="EMI" {{ old('specialization') == 'EMI' ? 'selected' : '' }}>EMI: Technicien en Electricité de Maintenance Industrielle</option>
                            </optgroup>
                        </optgroup>
                    </select>
                    @error('specialization')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Physical Criteria -->
                <div>
                    <label for="physical_condition" class="block text-sm font-medium text-gray-700 mb-1">Physical Condition</label>
                    <select name="physical_condition" id="physical_condition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Physical Condition</option>
                        <option value="excellent" {{ old('physical_condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="good" {{ old('physical_condition') == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="average" {{ old('physical_condition') == 'average' ? 'selected' : '' }}>Average</option>
                        <option value="poor" {{ old('physical_condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                    @error('physical_condition')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Disability status is already included above -->

                <!-- Family Criteria -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Family Status</label>
                    <div class="space-y-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="family_status_orphan" name="family_status[]" type="checkbox" value="orphan" {{ is_array(old('family_status')) && in_array('orphan', old('family_status')) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="family_status_orphan" class="font-medium text-gray-700">Orphan</label>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="family_status_single_parent" name="family_status[]" type="checkbox" value="single_parent" {{ is_array(old('family_status')) && in_array('single_parent', old('family_status')) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="family_status_single_parent" class="font-medium text-gray-700">Living with a single parent</label>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="family_status_divorced_deceased" name="family_status[]" type="checkbox" value="divorced_deceased" {{ is_array(old('family_status')) && in_array('divorced_deceased', old('family_status')) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="family_status_divorced_deceased" class="font-medium text-gray-700">Parents divorced or deceased</label>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="family_status_social_services" name="family_status[]" type="checkbox" value="social_services" {{ is_array(old('family_status')) && in_array('social_services', old('family_status')) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="family_status_social_services" class="font-medium text-gray-700">Social services beneficiary</label>
                            </div>
                        </div>
                    </div>
                    @error('family_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="siblings_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Siblings</label>
                    <input type="number" name="siblings_count" id="siblings_count" value="{{ old('siblings_count', 0) }}" min="0" placeholder="Enter number of siblings" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('siblings_count')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Parent or Guardian Info Section -->
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Parent or Guardian Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="guardian_first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="guardian_first_name" id="guardian_first_name" value="{{ old('guardian_first_name') }}" placeholder="Enter Parent's or Guardian's first name" class="w-full mb-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        @error('guardian_first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="guardian_last_name" class="block text-sm font-medium text-gray-700 mb-2">Family Name</label>
                        <input type="text" name="guardian_last_name" id="guardian_last_name" value="{{ old('guardian_last_name') }}" placeholder="Enter Parent's or Guardian's Family name" class="w-full mb-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        @error('guardian_last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                    
                    <div>
                        <label for="guardian_dob" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="guardian_dob" id="guardian_dob" value="{{ old('guardian_dob') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring mb-2 focus:ring-blue-500 focus:ring-opacity-50" required>
                        @error('guardian_dob')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="guardian_profession" class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                        <input type="text" name="guardian_profession" id="guardian_profession" value="{{ old('guardian_profession') }}" placeholder="Enter parent or guardian's profession" class="w-full mb-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        @error('guardian_profession')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="guardian_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="guardian_phone" id="guardian_phone" value="{{ old('guardian_phone') }}" placeholder="Enter parent or guardian's phone number" pattern="[0-9+]*" inputmode="tel" class="w-full mb-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" oninput="this.value = this.value.replace(/[^0-9+]/g, '');" required>
                        <p class="text-xs text-gray-500 mt-1">You can only enter numbers</p>
                        @error('guardian_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Supporting Documents Section -->
                <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Supporting Documents</h3>
                <div class="border border-gray-300 rounded-lg p-6 bg-gray-50">
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <label for="supporting_documents" class="block text-sm font-medium text-gray-700">Upload Documents (Max 5 files)</label>
                            <span id="file-count" class="text-xs text-gray-500">0/5 files</span>
                        </div>
                        <div class="flex items-center justify-center w-full">
                            <label for="supporting_documents" id="document-drop-area" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-100 transition-all duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="document-upload-prompt">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG, TXT, XLS, XLSX, CSV, ZIP (MAX. 10MB)</p>
                                </div>
                                <!-- File preview container (hidden by default) -->
                                <div id="document-preview-container" class="hidden w-full p-3">
                                    <div id="document-previews" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"></div>
                                </div>
                                <input id="supporting_documents" name="supporting_documents[]" type="file" class="hidden" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt,.xls,.xlsx,.csv,.zip" onchange="handleFileSelect()" />
                            </label>
                        </div>
                        @error('supporting_documents.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <h4 class="font-medium text-sm text-gray-700 mb-2">Required Documents:</h4>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            <li>CIN Copy</li>
                            <li>Proof of Residence</li>
                            <li>Income Proof</li>
                        </ul>
                        <p class="text-xs text-gray-500 mt-2">You can submit your application without these documents, but they may be requested later.</p>
                    </div>
                </div>
                

                <!-- JavaScript for file upload preview -->
                <script src="{{ asset('js/candidates/file-upload.js') }}"></script>
            </div>

            <!-- Declaration of Truthfulness -->            
            <div class="mt-8 mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-sm">
                <div class="flex items-start">
                    <div class="flex items-center h-5 mt-1">
                        <input id="declaration" name="declaration" type="checkbox" required class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3">
                        <label for="declaration" class="text-sm font-medium text-gray-700">
                            Declaration of Truthfulness
                        </label>
                        <p class="text-gray-600 text-sm mt-1">I hereby declare that all information provided is true and correct to the best of my knowledge.</p>
                        @error('declaration')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <a href="{{ route('candidates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Save Candidate
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Function to copy training_level value to academic_year field
    function updateAcademicYear() {
        const trainingLevel = document.getElementById('training_level').value;
        document.getElementById('academic_year').value = trainingLevel;
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateAcademicYear();
        
        // Make sure the form has the academic year value before submission
        document.getElementById('candidateForm').addEventListener('submit', function(e) {
            updateAcademicYear();
        });
    });
</script>

@endsection
