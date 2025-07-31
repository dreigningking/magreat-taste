<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Logo and Header -->
                <div class="text-center my-4">
                    <h2 class="fw-bold text-dark mb-2">Reset Password</h2>
                    <p class="text-muted">Create a new password for your account</p>
                </div>

                <!-- Reset Password Form -->
                <div class="card shadow border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <form wire:submit="resetPassword">
                            @csrf
                            
                            <!-- Password Reset Token -->
                            <input type="hidden" wire:model="token" value="{{ request()->route('token') }}">
                            
                            <!-- Email Address -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-dark">
                                    Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           id="email" 
                                           wire:model="email" 
                                           value="{{ old('email', request()->email) }}" 
                                           class="form-control border-start-0 ps-0" 
                                           required 
                                           readonly>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-2 d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- New Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-dark">
                                    New Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           id="password" 
                                           wire:model="password" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="••••••••" 
                                           required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-2 d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Confirm New Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold text-dark">
                                    Confirm New Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           wire:model="password_confirmation" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="••••••••" 
                                           required>
                                </div>
                            </div>
                            
                            <!-- Reset Password Button -->
                            <button type="submit" 
                                    class="btn btn-primary w-100 py-3 fw-semibold mb-4">
                                <span class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-key me-2"></i>
                                    Reset Password
                                </span>
                            </button>
                        </form>

                        <!-- Back to Login Link -->
                        <div class="text-center">
                            <a wire:navigate 
                               href="{{ route('login') }}" 
                               class="text-decoration-none fw-semibold text-primary d-flex align-items-center justify-content-center">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Login
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Remember your password? 
                        <a wire:navigate 
                           href="{{ route('login') }}" 
                           class="text-decoration-none text-primary">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important;
}


/* Animation for page load */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.min-vh-100 {
    animation: fadeInUp 0.6s ease-out;
}
</style>
@endpush