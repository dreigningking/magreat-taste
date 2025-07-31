<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Logo and Header -->
                <div class="text-center my-4">
                    <h2 class="fw-bold text-dark mb-2">Verify Your Email</h2>
                    <p class="text-muted">We need to verify your email address to continue.</p>
                </div>

                <!-- Verify Email Form -->
                <div class="card shadow border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <p class="text-muted">
                                We will send a verification code to your email address:
                                <span class="fw-semibold text-dark">{{ $user->email }}</span>
                            </p>
                            <div class="mt-2">
                                <button class="btn btn-link text-primary p-0" wire:click="$set('showEditEmail', true)">
                                    Edit email
                                </button>
                            </div>
                        </div>

                        @if ($otp_response)
                            <div class="alert alert-success mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span>{{ $otp_response }}</span>
                                </div>
                            </div>
                        @endif
                        @if ($otp_error)
                            <div class="alert alert-danger mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <span>{{ $otp_error }}</span>
                                </div>
                            </div>
                        @endif

                        @if (! $showCodeInput)
                            <div class="d-flex flex-column align-items-center gap-3">
                                <button type="button" 
                                        class="btn btn-primary w-100 py-3 fw-semibold" 
                                        wire:click="otp_send">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Get Verification Code
                                </button>
                                @if (session()->has('otp_sent'))
                                    <button type="button" 
                                            class="btn btn-secondary w-100 py-3 fw-semibold" 
                                            wire:click="$set('showCodeInput', true)">
                                        <i class="fas fa-check me-2"></i>
                                        I've received the code
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center gap-3">
                                <div class="w-100">
                                    <input type="text" 
                                           class="form-control" 
                                           placeholder="Enter verification code" 
                                           wire:model="code">
                                </div>
                                <button type="button" 
                                        class="btn btn-primary w-100 py-3 fw-semibold" 
                                        wire:click="otp_verify">
                                    <i class="fas fa-check me-2"></i>
                                    Validate Code
                                </button>
                                <button type="button" 
                                        class="btn btn-link text-primary" 
                                        wire:click="otp_send">
                                    <i class="fas fa-redo me-1"></i>
                                    Resend code
                                </button>
                            </div>
                        @endif
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

<!-- Edit Email Modal -->
@if ($showEditEmail)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Email Address</h5>
                    <button type="button" class="btn-close" wire:click="$set('showEditEmail', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" 
                               id="email" 
                               class="form-control" 
                               wire:model="email">
                        @error('email')
                            <div class="text-danger small mt-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-secondary" 
                            wire:click="$set('showEditEmail', false)">
                        Cancel
                    </button>
                    <button type="button" 
                            class="btn btn-primary" 
                            wire:click.prevent="saveEmail">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

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