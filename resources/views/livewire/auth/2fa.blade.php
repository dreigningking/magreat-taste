<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Logo and Header -->
                <div class="text-center my-4">
                    <h2 class="fw-bold text-dark mb-2">Two-Factor Authentication</h2>
                    <p class="text-muted">A verification code has been sent to your email address.</p>
                </div>

                <!-- 2FA Form -->
                <div class="card shadow border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <form wire:submit.prevent="verify">
                            <!-- Verification Code Field -->
                            <div class="mb-4">
                                <label for="code" class="form-label fw-semibold text-dark">
                                    Verification Code
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-shield-alt text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           id="code" 
                                           class="form-control border-start-0 ps-0" 
                                           required 
                                           autofocus 
                                           wire:model="code"
                                           placeholder="Enter verification code">
                                </div>
                                @if($otp_error)
                                    <div class="text-danger small mt-2 d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $otp_error }}
                                    </div>
                                @endif
                                @if($otp_response)
                                    <div class="text-success small mt-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $otp_response }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Verify Button -->
                            <button type="submit" 
                                    class="btn btn-primary w-100 py-3 fw-semibold mb-4">
                                <i class="fas fa-check me-2"></i>
                                Verify
                            </button>
                        </form>

                        <!-- Resend Code Link -->
                        <div class="text-center">
                            <button type="button" 
                                    class="btn btn-link text-primary" 
                                    wire:click="resend">
                                <i class="fas fa-redo me-1"></i>
                                Resend code
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Having trouble? 
                        <a wire:navigate 
                           href="{{ route('login') }}" 
                           class="text-decoration-none text-primary">
                            Contact support
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