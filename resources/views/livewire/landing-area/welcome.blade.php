<div>
     <!-- Hero Section -->
     <section class="hero" id="home">
        <div class="hero-content">
            <h1>Unforgettable Culinary Experiences</h1>
            <p>Gourmet meals - perfect for every occasion.</p>
            <a href="#menu" class="btn btn-warning btn-lg">Order Your Meal</a>
        </div>
    </section>

    <!-- About Chef -->
    <section class="container" id="about">
        <div class="chef-profile">
            <div class="row">
                
                <div class="col-md-6">
                    <div class="chef-info">
                        <h2 class="chef-name">Meet Chef Margaret</h2>
                        <p>With over 15 years of culinary experience, Chef Margaret brings passion and creativity to every dish. Trained in Nigeria and Asia, and having worked in home and abroads, she now focuses on creating bespoke dining experiences for clients.</p>
                        <p>Specializing in African, Chinese, Indian, and Italian cuisine, Chef Margaret sources only the freshest local ingredients to create memorable meals tailored to your preferences.</p>
                        <div class="mt-4">
                            <h5>Signature Styles:</h5>
                            <div class="d-flex flex-wrap mt-3">
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">African Cuisine</span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">Chinese Cuisine</span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">Indian Cuisine</span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">Italian Cuisine</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="background-image: url('{{ asset('images/chefta.jpeg') }}'); background-size: cover; background-position: center;">
                    <!-- <img src="{{ asset('images/chefta.jpeg') }}" alt="Chef Margaret" class="chef-img"> -->
                </div>
            </div>
        </div>
    </section>

    @livewire('landing-area.menu-section')

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5" id="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Clients Say</h2>
            <div class="testimonial-carousel-container">
                <div class="testimonial-carousel" id="testimonialCarousel">
                    <!-- Slide 1 -->
                    <div class="testimonial-slide active">
                        <div class="row">
                            <!-- Mrs Oluwafunmilayo Idera -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    
                                    <div class="testimonial-img mb-4">
                                        <img src="{{ asset('images/faces/oluwafunmilayo.jpeg') }}" alt="Mrs Oluwafunmilayo Idera" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"Chef Margaret's catering for my son's wedding was absolutely phenomenal! The chinese food was the talk of the evening. Every dish was perfectly executed and the presentation was stunning."</p>
                                        <h5 class="testimonial-name">Mrs Oluwafunmilayo Idera</h5>
                                        <p class="testimonial-title text-muted">Wedding Client</p>
                                        <div class="testimonial-rating">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Ezetu Joseph -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="{{ asset('images/faces/joseph.png') }}" alt="Ezetu Joseph" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"The corporate event catering exceeded all expectations. Chef Margaret's attention to detail and ability to accommodate dietary restrictions made our event a huge success."</p>
                                        <h5 class="testimonial-name">Ezetu Joseph</h5>
                                        <p class="testimonial-title text-muted">Corporate Event Planner</p>
                                        <div class="testimonial-rating">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Mr Bello Abiodun -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="{{ asset('images/faces/bello.png') }}" alt="Ezetu Joseph" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"I've been ordering Chef Margaret's artisan bread basket for my family gatherings. The quality and taste are consistently outstanding. Highly recommend!"</p>
                                        <h5 class="testimonial-name">Mr Bello Abiodun</h5>
                                        <p class="testimonial-title text-muted">Housewarming Client</p>
                                        <div class="testimonial-rating">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slide 2 -->
                    <div class="testimonial-slide">
                        <div class="row">
                            <!-- Pastor Lilian Nnogo -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="{{ asset('images/faces/lilian.jpg') }}" alt="Pastor Lilian Nnogo" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"Chef Margaret's jollof rice is absolutely divine! She catered our anniversary party and everyone was raving about the authentic flavors and perfect execution."</p>
                                        <h5 class="testimonial-name">Pastor Lilian Nnogo</h5>
                                        <p class="testimonial-title text-muted">Anniversary Celebration</p>
                                        <div class="testimonial-rating">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Lisa Martinez -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="{{ asset('images/faces/face2.jpg') }}" alt="Lisa Martinez" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"Now i can make different kinds of African food and soup. Thanks to Chef Margaret."</p>
                                        <h5 class="testimonial-name">Rose Clement</h5>
                                        <p class="testimonial-title text-muted">Culinary Student</p>
                                        <div class="testimonial-rating">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Robert Wilson -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="{{ asset('images/faces/face14.jpg') }}" alt="Robert Wilson" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"We had a light breakfast at work and her setting was so nice. I will definitely order from her again."</p>
                                        <h5 class="testimonial-name">Robert Wilson</h5>
                                        <p class="testimonial-title text-muted">Corporate Client</p>
                                        <div class="testimonial-rating">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <button class="testimonial-nav-btn prev-btn" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="testimonial-nav-btn next-btn" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Indicators -->
                <div class="testimonial-indicators">
                    <span class="indicator active" data-slide="0"></span>
                    <span class="indicator" data-slide="1"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    @livewire('landing-area.contact-section')
</div>
@push('styles')

@endpush
@push('scripts')
<script>
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
</script>
@endpush