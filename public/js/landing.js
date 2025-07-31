
// Simple cart functionality
document.addEventListener('DOMContentLoaded', function () {
    const addToCartBtn = document.getElementById('addToCartBtn');
    const cartBadge = document.querySelector('.cart-badge');
    const cartItems = document.querySelector('.cart-items');
    const cartSummary = document.querySelector('.cart-summary');
    const incrementBtn = document.getElementById('increment');
    const decrementBtn = document.getElementById('decrement');
    const quantityInput = document.getElementById('quantity');
    let cartCount = 0;
    let cartTotal = 0;
    let cartItemsData = []; // Store cart items data
    let checkoutModalInitialized = false; // Flag to prevent multiple initializations

    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const mealCards = document.querySelectorAll('.menu-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            mealCards.forEach(card => {
                const category = card.parentElement.getAttribute('data-category');
                if (filter === 'all' || filter === category) {
                    card.parentElement.style.display = 'block';
                } else {
                    card.parentElement.style.display = 'none';
                }
            });
        });
    });

    // Quantity controls
    if (incrementBtn && decrementBtn) {
        incrementBtn.addEventListener('click', function () {
            quantityInput.value = parseInt(quantityInput.value) + 1;
        });

        decrementBtn.addEventListener('click', function () {
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        });
    }

    // Add to cart button event
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function () {
            cartCount++;
            cartBadge.textContent = cartCount;

            // Get meal details
            const mealName = document.querySelector('#mealModal .modal-title').textContent;
            const mealPrice = parseFloat(document.querySelector('.pricing-tier.active .tier-price').textContent.replace('$', ''));
            const mealSize = document.querySelector('.pricing-tier.active .tier-title').textContent;
            const quantity = parseInt(quantityInput.value);
            const totalPrice = mealPrice * quantity;

            // Add item to cart data array
            const cartItem = {
                name: mealName,
                size: mealSize,
                price: mealPrice,
                quantity: quantity,
                totalPrice: totalPrice
            };
            cartItemsData.push(cartItem);

            // Update cart total
            cartTotal += totalPrice;

            // Add item to cart display
            if (cartItems.querySelector('.text-center')) {
                cartItems.innerHTML = '';
            }

            cartItems.innerHTML += `
                        <div class="cart-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>${mealName}</h6>
                                    <p class="text-muted mb-0">${mealSize} × ${quantity}</p>
                                </div>
                                <div>
                                    <span class="fw-bold">$${totalPrice.toFixed(2)}</span>
                                    <span class="remove-item ms-3" data-index="${cartItemsData.length - 1}"><i class="fas fa-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    `;

            // Update cart summary
            updateCartSummary();

            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function () {
                    const index = parseInt(this.getAttribute('data-index'));
                    const item = this.closest('.cart-item');
                    const itemPrice = cartItemsData[index].totalPrice;

                    // Remove from data array
                    cartItemsData.splice(index, 1);

                    // Update cart count and total
                    cartTotal -= itemPrice;
                    cartCount--;
                    cartBadge.textContent = cartCount;

                    // Remove from display
                    item.remove();

                    // Update remove button indices
                    document.querySelectorAll('.remove-item').forEach((btn, i) => {
                        btn.setAttribute('data-index', i);
                    });

                    if (cartItems.children.length === 0) {
                        cartItems.innerHTML = `
                                    <div class="text-center py-5">
                                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Your cart is empty</p>
                                    </div>
                                `;
                        updateCartSummary();
                    } else {
                        updateCartSummary();
                    }
                });
            });
        });
    }

    // Function to update cart summary
    function updateCartSummary() {
        const tax = cartTotal * 0.08;
        const grandTotal = cartTotal + tax;

        cartSummary.innerHTML = `
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>$${cartTotal.toFixed(2)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (8%):</span>
                        <span>$${tax.toFixed(2)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 fw-bold">
                        <span>Total:</span>
                        <span>$${grandTotal.toFixed(2)}</span>
                    </div>
                    <button class="btn btn-primary w-100 py-3" data-bs-toggle="modal" data-bs-target="#checkoutModal" data-bs-dismiss="offcanvas">Checkout</button>
                `;
    }

    // Function to update checkout modal with cart data
    function updateCheckoutModal() {
        const orderItemsContainer = document.querySelector('#checkoutModal .order-items');
        const orderSummaryContainer = document.querySelector('#checkoutModal .order-summary');

        if (cartItemsData.length === 0) {
            orderItemsContainer.innerHTML = '<p class="text-muted">No items in cart</p>';
            orderSummaryContainer.innerHTML = `
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8%):</span>
                            <span>$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 fw-bold">
                            <span>Grand Total:</span>
                            <span>$0.00</span>
                        </div>
                    `;
            return;
        }

        // Update order items
        let orderItemsHTML = '';
        cartItemsData.forEach(item => {
            orderItemsHTML += `
                        <div class="d-flex justify-content-between mb-2">
                            <span>${item.name} (${item.size}, x${item.quantity})</span>
                            <span>$${item.totalPrice.toFixed(2)}</span>
                        </div>
                    `;
        });
        orderItemsContainer.innerHTML = orderItemsHTML;

        // Update order summary
        const tax = cartTotal * 0.08;
        const grandTotal = cartTotal + tax;

        orderSummaryContainer.innerHTML = `
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>$${cartTotal.toFixed(2)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (8%):</span>
                        <span>$${tax.toFixed(2)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 fw-bold">
                        <span>Grand Total:</span>
                        <span>$${grandTotal.toFixed(2)}</span>
                    </div>
                `;
    }

    // Update checkout modal when it's opened
    const checkoutModal = document.querySelector('#checkoutModal');
    if (checkoutModal && !checkoutModalInitialized) {
        checkoutModal.addEventListener('show.bs.modal', function () {
            updateCheckoutModal();
        });
        checkoutModalInitialized = true;
    }

    // Pricing tier selection
    document.querySelectorAll('.pricing-tier').forEach(tier => {
        tier.addEventListener('click', function () {
            document.querySelectorAll('.pricing-tier').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const price = this.getAttribute('data-price');
            document.querySelector('.text-primary').textContent = `$${price}`;
        });
    });

    // Meal card click events
    mealCards.forEach(card => {
        card.addEventListener('click', function () {
            const mealId = this.getAttribute('data-id');
            // In a real app, you would fetch meal data based on ID
            // For demo, we'll just set the modal content for the first meal
            const modalTitle = document.querySelector('#mealModal .modal-title');
            const modalImg = document.querySelector('#mealModal img');
            const modalContent = document.querySelector('#mealModal .col-md-6 h4');

            if (mealId === '1') {
                modalTitle.textContent = 'Truffle Arancini';
                modalImg.src = 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                modalContent.textContent = 'Truffle Arancini';
            } else if (mealId === '2') {
                modalTitle.textContent = 'Seafood Paella';
                modalImg.src = 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                modalContent.textContent = 'Seafood Paella';
            } else if (mealId === '3') {
                modalTitle.textContent = 'Chocolate Soufflé';
                modalImg.src = 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                modalContent.textContent = 'Chocolate Soufflé';
            } else if (mealId === '4') {
                modalTitle.textContent = 'Artisan Bread Basket';
                modalImg.src = 'https://images.unsplash.com/photo-1594041680534-e8c8cdebd659?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                modalContent.textContent = 'Artisan Bread Basket';
            } else if (mealId === '5') {
                modalTitle.textContent = 'Wild Mushroom Risotto';
                modalImg.src = 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                modalContent.textContent = 'Wild Mushroom Risotto';
            } else if (mealId === '6') {
                modalTitle.textContent = 'Beef Carpaccio';
                modalImg.src = 'https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                modalContent.textContent = 'Beef Carpaccio';
            }
        });
    });
});

// Testimonial Carousel Functionality
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('testimonialCarousel');
    const slides = document.querySelectorAll('.testimonial-slide');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const indicators = document.querySelectorAll('.indicator');

    let currentSlide = 0;
    const totalSlides = slides.length;

    // Function to show slide
    function showSlide(slideIndex) {
        // Hide all slides
        slides.forEach(slide => {
            slide.classList.remove('active');
        });

        // Show current slide
        slides[slideIndex].classList.add('active');

        // Update indicators
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === slideIndex);
        });

        currentSlide = slideIndex;
    }

    // Next slide
    function nextSlide() {
        const nextIndex = (currentSlide + 1) % totalSlides;
        showSlide(nextIndex);
    }

    // Previous slide
    function prevSlide() {
        const prevIndex = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(prevIndex);
    }

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }

    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            showSlide(index);
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') {
            prevSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
        }
    });
});


