<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Logo and Header -->
                <div class="text-center my-4">
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: linear-gradient(135deg, #6f42c1 0%, #0d6efd 100%);">
                        <i class="fas fa-unlock-alt text-white fs-2"></i>
                    </div>
                    <h2 class="fw-bold text-dark mb-2">Forgot Your Password?</h2>
                    <p class="text-muted">Enter your email address and we'll send you a link to reset your password</p>
                </div>

                <!-- Forgot Password Form -->
                <div class="card shadow border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <form wire:submit.prevent="sendPasswordResetLink">
                            @csrf
                            
                            @if (session('status'))
                                <div class="alert alert-success mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <span>{{ session('status') }}</span>
                                    </div>
                                </div>
                            @endif
                            
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
                            
                            <!-- Send Reset Link Button -->
                            <button id="forgotPasswordBtn" type="submit" 
                                    class="btn btn-primary w-100 py-3 fw-semibold mb-4"
                                    wire:loading.attr="disabled" wire:target="sendPasswordResetLink">
                                <span class="normal-text d-flex align-items-center justify-content-center" wire:loading.remove wire:target="sendPasswordResetLink">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Reset Link
                                </span>
                                <span class="submitting-text d-flex align-items-center justify-content-center" wire:loading wire:target="sendPasswordResetLink" style="display:none">
                                    <div class="spinner-border spinner-border-sm me-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Sending...
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

/* Button submit JS-controlled state */
#forgotPasswordBtn .submitting-text { display: none !important; }
#forgotPasswordBtn.is-submitting .submitting-text { display: flex !important; }
#forgotPasswordBtn.is-submitting .normal-text { display: none !important; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('forgotPasswordBtn');
        if (!btn) return;

        btn.addEventListener('click', function() {
            btn.classList.add('is-submitting');
        });

        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', function(message, component) {
                btn.classList.remove('is-submitting');
            });
        });
    });
</script>
@endpush
