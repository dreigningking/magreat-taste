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
                <div class="col-md-5 p-0">
                    <img src="{{ asset('images/chef.jpeg') }}" alt="Chef Margaret" class="chef-img">
                </div>
                <div class="col-md-7">
                    <div class="chef-info">
                        <h2 class="chef-name">Meet Chef Margaret</h2>
                        <p>With over 15 years of culinary experience, Chef Margaret brings passion and creativity to every dish. Trained in Paris and having worked in Michelin-starred restaurants, she now focuses on creating bespoke dining experiences for clients.</p>
                        <p>Specializing in farm-to-table cuisine, Chef Margaret sources only the freshest local ingredients to create memorable meals tailored to your preferences.</p>
                        <div class="mt-4">
                            <h5>Signature Styles:</h5>
                            <div class="d-flex flex-wrap mt-3">
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">French Cuisine</span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">Mediterranean</span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">Plant-Based</span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">Artisan Baking</span>
                            </div>
                        </div>
                    </div>
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
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="{{ asset('images/faces/joseph.png') }}" alt="Ezetu Joseph" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"Chef Margaret's catering for our wedding was absolutely phenomenal! The truffle arancini was the talk of the evening. Every dish was perfectly executed and the presentation was stunning."</p>
                                        <h5 class="testimonial-name">Sarah Johnson</h5>
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
                            
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Michael Chen" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"The corporate event catering exceeded all expectations. Chef Margaret's attention to detail and ability to accommodate dietary restrictions made our event a huge success."</p>
                                        <h5 class="testimonial-name">Michael Chen</h5>
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
                            
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Emily Rodriguez" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"I've been ordering Chef Margaret's artisan bread basket for my family gatherings. The quality and taste are consistently outstanding. Highly recommend!"</p>
                                        <h5 class="testimonial-name">Emily Rodriguez</h5>
                                        <p class="testimonial-title text-muted">Regular Client</p>
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
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="David Thompson" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"Chef Margaret's seafood paella is absolutely divine! She catered our anniversary party and everyone was raving about the authentic flavors and perfect execution."</p>
                                        <h5 class="testimonial-name">David Thompson</h5>
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
                            
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Lisa Martinez" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"The chocolate soufflé was the perfect ending to our dinner party. Chef Margaret's attention to detail and presentation skills are unmatched in the industry."</p>
                                        <h5 class="testimonial-name">Lisa Martinez</h5>
                                        <p class="testimonial-title text-muted">Dinner Party Host</p>
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
                            
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card text-center">
                                    <div class="testimonial-img mb-4">
                                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Robert Wilson" class="rounded-circle" width="80" height="80">
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text mb-4">"Chef Margaret's wild mushroom risotto is simply incredible. The depth of flavor and creamy texture made it the highlight of our corporate luncheon."</p>
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

@endpush