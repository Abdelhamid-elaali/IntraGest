<x-layouts.guest>
    <!-- Logo and Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-medium text-gray-900 mb-2">Password Recovery</h1>
        <h2 class="text-xl font-extrabold text-primary-600">IntraGest Management System</h2>
    </div>

    <!-- Password Reset Form Card -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
        <h3 class="text-xl font-semibold text-gray-900 mb-6 py-4 border-b-4 border-dotted border-blue-600">Reset Your Password</h3>
        
        <div class="mb-6 text-sm text-gray-600">
            <div class="flex items-start">
                <div class="flex-shrink-0 pt-0.5">
                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="ml-3">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </p>
            </div>
        </div>

        <!-- Status Message -->
        @if (session('status'))
            <div class="mb-2 p-2 rounded-lg bg-green-50 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

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
                        value="{{ old('email') }}"
                        required
                        autofocus
                    />
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200"
            >
                Email Password Reset Link
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

    <!-- Security Notice -->
    <div class="mt-4 bg-blue-50 rounded-lg p-4 border border-blue-100">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Security Notice</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>For security reasons, we will only send a password reset link to email addresses that are registered in our system.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-2 text-center text-sm text-gray-600">
        &copy; {{ date('Y') }} IntraGest. All rights reserved.
    </div>
</x-layouts.guest>
