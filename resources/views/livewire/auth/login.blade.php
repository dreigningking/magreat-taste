<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Logo and Header -->
                <div class="text-center my-4">
                    
                    <h2 class="fw-bold text-dark mb-2">Welcome Back</h2>
                    <p class="text-muted">Sign in to access your account</p>
                </div>

                <!-- Login Form -->
                <div class="card shadow border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <form wire:submit="signin">
                            @csrf

                            <!-- Email/Username Field -->
                            <div class="mb-4">
                                <label for="login" class="form-label fw-semibold text-dark">
                                    Email or Username
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           id="login" 
                                           wire:model="login" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="your@email.com or username" 
                                           required>
                                </div>
                                @error('login')
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

                            <!-- Remember Me and Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input id="remember" 
                                           wire:model="remember" 
                                           type="checkbox" 
                                           class="form-check-input">
                                    <label for="remember" class="form-check-label text-muted">
                                        Remember me
                                    </label>
                                </div>
                                <div>
                                    <a wire:navigate 
                                       href="{{route('password.request')}}" 
                                       class="text-decoration-none text-primary fw-medium">
                                        Forgot password?
                                    </a>
                                </div>
                            </div>

                            <!-- Sign In Button -->
                            <button type="submit" 
                                    class="btn btn-primary w-100 py-3 fw-semibold mb-4">
                                <i class="fas fa-sign-in-alt me-2"></i>  Sign In
                            </button>
                        </form>

                        <!-- Social Login Divider -->
                        <div class="position-relative mb-4">
                            <hr class="my-4">
                            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                                <span class="text-muted small fw-medium">Or continue with</span>
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

                        <!-- Sign Up Link -->
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Don't have an account?
                                <a wire:navigate 
                                   href="{{route('register')}}" 
                                   class="text-decoration-none fw-semibold text-primary">
                                    Sign up now
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        By signing in, you agree to our 
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