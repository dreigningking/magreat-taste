<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma'Great Taste | Personal Chef & Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="icon" href="{{ asset('images/logo.jpeg') }}" type="image/x-icon">
    @livewireStyles
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand py-0" href="#">
                <img src="{{ asset('images/logo.jpeg') }}" style="width: 53px; height: 53px;" alt="Ma'Great Taste" class="logo">
                Ma<span>'</span>Great Taste
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ request()->routeIs('index') ? '#home' : route('index') . '#home' }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ request()->routeIs('index') ? '#about' : route('index') . '#about' }}">About Chef</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ request()->routeIs('index') ? '#menu' : route('index') . '#menu' }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ request()->routeIs('index') ? '#testimonials' : route('index') . '#testimonials' }}">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ request()->routeIs('index') ? '#contact' : route('index') . '#contact' }}">Contact</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('blog') }}">Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <!-- Cart Button -->
    

    <!-- Meal Modal -->
    

    <!-- Cart Offcanvas -->
    @livewire('landing-area.cart-canvas')

    <!-- Checkout Modal -->
    @livewire('landing-area.checkout')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h4>Ma'Great Taste</h4>
                    <p>Meals crafted with passion and precision by Chef Margaret. Available for private events, catering, and personal orders.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Service Areas</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Greater Metropolitan Area</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Surrounding Counties</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Special Events Nationwide</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Chef Margaret</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> (555) 123-4567</li>
                        <li><i class="fas fa-envelope me-2"></i> margaret@chefsdelight.com</li>
                        <li><i class="fas fa-calendar me-2"></i> Bookings: 3+ weeks in advance</li>
                    </ul>
                </div>
            </div>
            <div class="copyright text-center">
                <p>&copy; 2023 Ma'Great Taste Catering. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script data-navigate-once src="{{ asset('vendors/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
    <script src="{{ asset('vendors/jquery.min.js') }}"></script>
    @livewireScripts
    <script>
		$(document).ready(function() {
			Livewire.on('closeModal', function(data) {
				console.log('Close modal event received:', data);
				const modalId = data[0].modalId;

				const modalEl = document.getElementById(modalId);

				// Check if Bootstrap modal instance already exists
				let modalInstance = bootstrap.Modal.getInstance(modalEl);
				if (!modalInstance) {
					// If not, create a new instance (Bootstrap 5)
					modalInstance = new bootstrap.Modal(modalEl);
				}
				// Hide the modal with Bootstrap 5 API
				modalInstance.hide();
			});
		});
	</script>
    @stack('scripts')
</body>
</html>