<main class="container mx-auto px-4 py-12">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-shield-keyhole-line text-primary text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ __('Confirm Password') }}</h1>
                    <p class="text-gray-600 mt-2">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>
                </div>
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <div class="flex">
                            <div>
                                <p class="text-sm text-green-700">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-lock-line text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-button focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" required autocomplete="current-password">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="ri-eye-off-line text-gray-400" id="passwordEyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-button hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        {{ __('Confirm') }}
                    </button>
                </form>
            </div>
        </div>
    </main>