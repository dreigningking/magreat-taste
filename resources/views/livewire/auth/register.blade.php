<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Logo and Header -->
                <div class="text-center my-4">
                    <h2 class="fw-bold text-dark mb-2">Create Your Account</h2>
                    <p class="text-muted">Join our community and start earning</p>
                </div>

                <!-- Register Form -->
                <div class="card shadow border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <form wire:submit="register">
                            @csrf

                            <!-- Full Name Field -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold text-dark">
                                    Full Name
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           id="name" 
                                           wire:model="name" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="John Doe" 
                                           required>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-2 d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email Field -->
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
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="your@email.com" 
                                           required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-2 d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-dark">
                                    Password
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

                            <!-- Confirm Password Field -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold text-dark">
                                    Confirm Password
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

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input id="terms" 
                                           wire:model="terms" 
                                           value="1" 
                                           type="checkbox" 
                                           class="form-check-input" 
                                           required>
                                    <label for="terms" class="form-check-label text-muted">
                                        I agree to the 
                                        <a href="#" class="text-decoration-none text-primary">Terms of Service</a> 
                                        and 
                                        <a href="#" class="text-decoration-none text-primary">Privacy Policy</a>
                                    </label>
                                </div>
                                @error('terms')
                                    <div class="text-danger small mt-2 d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Create Account Button -->
                            <button type="submit" 
                                    class="btn btn-primary w-100 py-3 fw-semibold mb-4">
                                <i class="fas fa-user-plus me-2"></i>  Create Account
                            </button>
                        </form>

                        <!-- Social Login Divider -->
                        <div class="position-relative mb-4">
                            <hr class="my-4">
                            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                                <span class="text-muted small fw-medium">Or sign up with</span>
                            </div>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <button type="button" 
                                        class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center">
                                    <i class="fab fa-google text-danger me-2"></i>
                                    <span class="small">Google</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" 
                                        class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center">
                                    <i class="fab fa-facebook text-primary me-2"></i>
                                    <span class="small">Facebook</span>
                                </button>
                            </div>
                        </div>

                        <!-- Sign In Link -->
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Already have an account?
                                <a wire:navigate 
                                   href="{{ route('login') }}" 
                                   class="text-decoration-none fw-semibold text-primary">
                                    Sign in
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        By creating an account, you agree to our 
                        <a href="#" class="text-decoration-none text-primary">Terms of Service</a> 
                        and 
                        <a href="#" class="text-decoration-none text-primary">Privacy Policy</a>
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