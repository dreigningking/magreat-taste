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
    <a href="#" class="cart-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span class="cart-badge">0</span>
    </a>

    <!-- Meal Modal -->
    <div class="modal fade" id="mealModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Truffle Arancini</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Truffle Arancini" class="img-fluid rounded mb-3">
                            
                            <div class="video-thumbnail position-relative mt-3" data-bs-toggle="modal" data-bs-target="#videoModal">
                                <img src="https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg" alt="Video Thumbnail" class="img-fluid rounded">
                                <div class="play-btn position-absolute top-50 start-50 translate-middle">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="position-absolute bottom-0 start-0 bg-dark bg-opacity-75 text-white p-2 w-100">
                                    Watch Preparation Video
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Truffle Arancini</h4>
                            <p class="text-muted">Crispy risotto balls with black truffle and melted mozzarella</p>
                            <p>Our signature arancini features creamy saffron risotto blended with black truffle and fresh mozzarella, coated in panko breadcrumbs and fried to golden perfection. Served with a truffle aioli dipping sauce.</p>
                            <p><strong>Ingredients:</strong> Arborio rice, black truffle, mozzarella, parmesan, saffron, panko breadcrumbs, truffle oil, garlic aioli.</p>
                            
                            <div class="mt-4">
                                <h5>Select Size</h5>
                                <div class="pricing-tiers">
                                    <div class="pricing-tier" data-size="small" data-price="28">
                                        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Small" class="tier-image">
                                        <div class="tier-title">Small Tray</div>
                                        <div class="tier-desc">Serves 4-6</div>
                                        <div class="tier-price">$28</div>
                                    </div>
                                    <div class="pricing-tier active" data-size="medium" data-price="45">
                                        <img src="https://images.unsplash.com/photo-1626082927389-6cd097cee6a6?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Medium" class="tier-image">
                                        <div class="tier-title">Medium Tray</div>
                                        <div class="tier-desc">Serves 8-10</div>
                                        <div class="tier-price">$45</div>
                                    </div>
                                    <div class="pricing-tier" data-size="large" data-price="65">
                                        <img src="https://images.unsplash.com/photo-1556911220-ef412aea183d?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Large" class="tier-image">
                                        <div class="tier-title">Large Tray</div>
                                        <div class="tier-desc">Serves 15-20</div>
                                        <div class="tier-price">$65</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <h4 class="text-primary">$45</h4>
                                <div class="input-group w-50">
                                    <button class="btn btn-outline-secondary" type="button" id="decrement">-</button>
                                    <input type="number" class="form-control text-center" value="1" min="1" id="quantity">
                                    <button class="btn btn-outline-secondary" type="button" id="increment">+</button>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 mt-3 py-3" data-bs-dismiss="modal" id="addToCartBtn">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal fade video-modal" id="videoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Truffle Arancini Preparation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Your Order</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="cart-items">
                <!-- Cart items will be added here dynamically -->
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Your cart is empty</p>
                </div>
            </div>
            <div class="cart-summary mt-auto pt-3 border-top">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax (8%):</span>
                    <span>$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3 fw-bold">
                    <span>Total:</span>
                    <span>$0.00</span>
                </div>
                <button class="btn btn-primary w-100 py-3" data-bs-toggle="modal" data-bs-target="#checkoutModal" data-bs-dismiss="offcanvas">Checkout</button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complete Your Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <h5 class="mb-4">Customer Information</h5>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Delivery Address</label>
                                    <textarea class="form-control" id="address" rows="3" required></textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="date" class="form-label">Delivery Date</label>
                                        <input type="date" class="form-control" id="date" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="time" class="form-label">Delivery Time</label>
                                        <input type="time" class="form-control" id="time" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="instructions" class="form-label">Special Instructions</label>
                                    <textarea class="form-control" id="instructions" rows="2"></textarea>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="col-md-6">
                            <h5 class="mb-4">Order Summary</h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="order-items mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Truffle Arancini (Medium, x1)</span>
                                            <span>$45.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Seafood Paella (Large, x1)</span>
                                            <span>$65.00</span>
                                        </div>
                                    </div>
                                    <div class="order-summary">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span>$110.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax (8%):</span>
                                            <span>$8.80</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3 fw-bold">
                                            <span>Grand Total:</span>
                                            <span>$118.80</span>
                                        </div>
                                    </div>
                                    <div class="payment-options">
                                        <h6 class="mb-3">Payment Method</h6>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked>
                                            <label class="form-check-label" for="creditCard">
                                                Credit/Debit Card
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="paypal">
                                            <label class="form-check-label" for="paypal">
                                                PayPal
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="cash">
                                            <label class="form-check-label" for="cash">
                                                Cash on Delivery
                                            </label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-4 py-3">Complete Payment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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