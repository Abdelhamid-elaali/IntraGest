@extends('layouts.app')


@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Add New Trainee</h1>
        <p class="text-gray-600">Enter the details of the new trainee below.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="Enter trainee's first name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Family Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="Enter trainee's family name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cin" class="block text-sm font-medium text-gray-700 mb-1">CIN ID</label>
                    <input type="text" name="cin" id="cin" value="{{ old('cin') }}" placeholder="Enter trainee's CIN ID" pattern="[A-Za-z0-9]+" title="Only letters and numbers allowed" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    <p class="text-xs text-gray-500 mt-1">Only letters and numbers are allowed, no symbols.</p>
                    @error('cin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter trainee's email address" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Enter trainee's phone number" pattern="[0-9+]*" inputmode="tel" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" oninput="this.value = this.value.replace(/[^0-9+]/g, '');" required>
                    <p class="text-xs text-gray-500 mt-1">Only numbers and the plus (+) symbol are allowed</p>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <select name="academic_year" id="academic_year" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Academic Year</option>
                        <option value="First Year" {{ old('academic_year') == 'First Year' ? 'selected' : '' }}>First Year</option>
                        <option value="Second Year" {{ old('academic_year') == 'Second Year' ? 'selected' : '' }}>Second Year</option>
                        <option value="Third Year" {{ old('academic_year') == 'Third Year' ? 'selected' : '' }}>Third Year</option>
                    </select>
                    @error('academic_year')
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
                    <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                    <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}" placeholder="Enter trainee's nationality" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('nationality')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('date_of_birth')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <div class="mt-2 flex items-center space-x-6">
                        <div class="flex items-center">
                            <input id="gender-male" name="gender" type="radio" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
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
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Enter trainee's full address" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="place_of_residence" class="block text-sm font-medium text-gray-700 mb-1">Place of Residence</label>
                    <input type="text" name="place_of_residence" id="place_of_residence" value="{{ old('place_of_residence') }}" placeholder="Enter trainee's place of residence" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('place_of_residence')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('students.index')}}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>    
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Trainee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
