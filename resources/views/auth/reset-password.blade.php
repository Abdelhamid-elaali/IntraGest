<x-layouts.guest>
    <!-- Logo and Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-medium text-gray-900 mb-2">Reset Password</h1>
        <h2 class="text-xl font-extrabold text-primary-600">IntraGest Management System</h2>
    </div>

    <!-- Password Reset Form Card -->
    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <h3 class="text-xl font-semibold text-gray-900 mb-6 py-4 border-b-4 border-dotted border-blue-600">Create New Password</h3>
        
        <div class="mb-6 text-sm text-gray-600">
            <div class="flex items-start">
                <div class="flex-shrink-0 pt-0.5">
                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="ml-3">
                    Please create a new secure password for your account. Your password should be at least 8 characters long and include a mix of letters, numbers, and special characters.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input pl-10 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                        placeholder="Enter your email address"
                        value="{{ old('email', request()->email) }}"
                        required
                        autofocus
                    />
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input pl-10 pr-10 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                        placeholder="Enter your new password"
                        required
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility('password', 'password-icon-show', 'password-icon-hide')">
                        <svg id="password-icon-show" class="h-5 w-5 text-gray-400 hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        <svg id="password-icon-hide" class="h-5 w-5 text-gray-400 hover:text-gray-600 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                        </svg>
                    </div>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-input pl-10 pr-10 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm"
                        placeholder="Confirm your new password"
                        required
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility('password_confirmation', 'confirm-icon-show', 'confirm-icon-hide')">
                        <svg id="confirm-icon-show" class="h-5 w-5 text-gray-400 hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        <svg id="confirm-icon-hide" class="h-5 w-5 text-gray-400 hover:text-gray-600 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Password Strength Indicator -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Password Strength</h4>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div id="strength-meter" class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="strength-progress" class="bg-red-500 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <span id="strength-text" class="ml-2 text-xs font-medium text-gray-500">Too weak</span>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1 ml-5 list-disc">
                        <li id="length-check" class="text-gray-400">At least 8 characters</li>
                        <li id="uppercase-check" class="text-gray-400">At least 1 uppercase letter</li>
                        <li id="lowercase-check" class="text-gray-400">At least 1 lowercase letter</li>
                        <li id="number-check" class="text-gray-400">At least 1 number</li>
                        <li id="special-check" class="text-gray-400">At least 1 special character</li>
                    </ul>
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                id="reset-button"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200"
            >
                Reset Password
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-700">
                    <span class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to login
                    </span>
                </a>
            </div>
        </form>
    </div>

    <!-- Security Tips -->
    <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-100">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Security Tips</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Use a unique password that you don't use for other accounts</li>
                        <li>Avoid using personal information in your password</li>
                        <li>Consider using a password manager to generate and store strong passwords</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-6 text-center text-sm text-gray-600">
        &copy; {{ date('Y') }} IntraGest. All rights reserved.
    </div>

    <!-- Password Toggle and Strength Check Script -->
    <script>
        function togglePasswordVisibility(inputId, showIconId, hideIconId) {
            const passwordInput = document.getElementById(inputId);
            const showIcon = document.getElementById(showIconId);
            const hideIcon = document.getElementById(hideIconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            }
        }

        // Password strength checker
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const strengthMeter = document.getElementById('strength-progress');
            const strengthText = document.getElementById('strength-text');
            const lengthCheck = document.getElementById('length-check');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const lowercaseCheck = document.getElementById('lowercase-check');
            const numberCheck = document.getElementById('number-check');
            const specialCheck = document.getElementById('special-check');
            const resetButton = document.getElementById('reset-button');
            
            passwordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                let strength = 0;
                
                // Check length
                if (password.length >= 8) {
                    strength += 20;
                    lengthCheck.classList.remove('text-gray-400');
                    lengthCheck.classList.add('text-green-600');
                } else {
                    lengthCheck.classList.remove('text-green-600');
                    lengthCheck.classList.add('text-gray-400');
                }
                
                // Check uppercase
                if (/[A-Z]/.test(password)) {
                    strength += 20;
                    uppercaseCheck.classList.remove('text-gray-400');
                    uppercaseCheck.classList.add('text-green-600');
                } else {
                    uppercaseCheck.classList.remove('text-green-600');
                    uppercaseCheck.classList.add('text-gray-400');
                }
                
                // Check lowercase
                if (/[a-z]/.test(password)) {
                    strength += 20;
                    lowercaseCheck.classList.remove('text-gray-400');
                    lowercaseCheck.classList.add('text-green-600');
                } else {
                    lowercaseCheck.classList.remove('text-green-600');
                    lowercaseCheck.classList.add('text-gray-400');
                }
                
                // Check numbers
                if (/[0-9]/.test(password)) {
                    strength += 20;
                    numberCheck.classList.remove('text-gray-400');
                    numberCheck.classList.add('text-green-600');
                } else {
                    numberCheck.classList.remove('text-green-600');
                    numberCheck.classList.add('text-gray-400');
                }
                
                // Check special characters
                if (/[^A-Za-z0-9]/.test(password)) {
                    strength += 20;
                    specialCheck.classList.remove('text-gray-400');
                    specialCheck.classList.add('text-green-600');
                } else {
                    specialCheck.classList.remove('text-green-600');
                    specialCheck.classList.add('text-gray-400');
                }
                
                // Update strength meter
                strengthMeter.style.width = strength + '%';
                
                // Update color and text based on strength
                if (strength <= 20) {
                    strengthMeter.className = 'bg-red-500 h-2.5 rounded-full';
                    strengthText.textContent = 'Too weak';
                    strengthText.className = 'ml-2 text-xs font-medium text-red-500';
                } else if (strength <= 40) {
                    strengthMeter.className = 'bg-orange-500 h-2.5 rounded-full';
                    strengthText.textContent = 'Weak';
                    strengthText.className = 'ml-2 text-xs font-medium text-orange-500';
                } else if (strength <= 60) {
                    strengthMeter.className = 'bg-yellow-500 h-2.5 rounded-full';
                    strengthText.textContent = 'Fair';
                    strengthText.className = 'ml-2 text-xs font-medium text-yellow-500';
                } else if (strength <= 80) {
                    strengthMeter.className = 'bg-blue-500 h-2.5 rounded-full';
                    strengthText.textContent = 'Good';
                    strengthText.className = 'ml-2 text-xs font-medium text-blue-500';
                } else {
                    strengthMeter.className = 'bg-green-500 h-2.5 rounded-full';
                    strengthText.textContent = 'Strong';
                    strengthText.className = 'ml-2 text-xs font-medium text-green-600';
                }
            });
        });
    </script>
</x-layouts.guest>
