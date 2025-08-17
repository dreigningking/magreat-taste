<div>
    <section class="menu-section" id="menu">
        <div class="container">
            <h2 class="section-title">Signature Creations</h2>

            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All Dishes</button>
                @foreach($categories as $category)
                    <button class="filter-btn" data-filter="{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            </div>

            <div class="row" wire:loading.class="opacity-50">
                <!-- Meal Cards -->
                @forelse($meals as $meal)
                    <div class="col-lg-4 col-md-6" data-category="{{ $meal->category->slug ?? 'uncategorized' }}">
                        <div class="menu-card" 
                             data-bs-toggle="modal" 
                             data-bs-target="#mealModal" 
                             wire:click="selectMeal({{ $meal->id }})"
                             data-meal-id="{{ $meal->id }}">
                        <div class="position-relative">
                                <img src="{{ $meal->image_url }}" 
                                     alt="{{ $meal->name }}" class="menu-card-img">
                            <div class="video-overlay">
                                    @if($meal->video_url)
                                <div class="play-btn">
                                    <i class="fas fa-play"></i>
                                </div>
                                    @else
                                        <div class="info-btn">
                                            <i class="fas fa-info"></i>
                            </div>
                                    @endif
                                </div>
                                <div class="price-badge">
                                    From {{ $meal->formatted_from_price ?? '0' }}
                                </div>
                        </div>
                        <div class="menu-card-body">
                                <h5 class="menu-card-title">{{ $meal->name }}</h5>
                                <p>{{ Str::limit($meal->description, 100) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-utensils text-muted" style="font-size: 3rem;"></i>
                        <h4 class="text-muted mt-3">No meals available</h4>
                        <p class="text-muted">Check back later for our delicious offerings!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    
    <!-- Meal Modal -->
    <div wire:ignore.self class="modal fade" id="mealModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMealTitle">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($modalVideoUrl)
                                <div id="modalVideoThumbnail" class="video-thumbnail position-relative mt-3" data-bs-toggle="modal" data-bs-target="#videoModal">
                                    <img src="{{ $modalMealImage }}" alt="Video Thumbnail" class="img-fluid rounded w-100 h-100 object-fit-cover">
                                <div class="play-btn position-absolute top-50 start-50 translate-middle">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="position-absolute bottom-0 start-0 bg-dark bg-opacity-75 text-white p-2 w-100">
                                    Watch Preparation Video
                                </div>
                            </div>
                            @else
                                <img id="modalMealImage" src="{{ $modalMealImage }}" alt="Meal Image" class="img-fluid rounded mb-3 w-100 h-100 object-fit-cover">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h4 id="modalMealName">{{ $modalMealName }}</h4>
                            <p class="text-muted" id="modalMealDescription">{{ $modalMealDescription }}</p>
                            <p id="modalMealFullDescription">{{ $modalMealFullDescription }}</p>
                            <p><strong>Category:</strong> <span id="modalMealCategory">{{ $modalMealCategory }}</span></p>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Select Food Items & Sizes</h5>
                            <div id="modalFoodItems">
                                @foreach($modalFoodItems as $food)
                                    @php
                                        // Find the lowest price size for this food
                                        $lowestPriceSize = collect($food['sizes'])->sortBy('price')->first();
                                        $lowestPrice = $lowestPriceSize['price'] ?? 0;
                                    @endphp
                                    <div class="food-item mb-4 p-3 border rounded">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <h6 class="mb-2">{{ $food['name'] }}</h6>
                                                <p class="text-muted mb-0 small">{{ $food['description'] ?? '' }}</p>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label small mb-2">Select Sizes & Quantities:</label>
                                                <div class="food-sizes-container">
                                                    <div class="food-sizes-scroll">
                                                        @foreach($food['sizes'] as $size)
                                                            @php
                                                                $isLowestPrice = $size['price'] == $lowestPrice;
                                                                $isSelected = isset($selectedSizes[$food['id']][$size['id']]) && $selectedSizes[$food['id']][$size['id']] > 0;
                                                                $quantity = $selectedSizes[$food['id']][$size['id']] ?? 0;
                                                            @endphp
                                                            <div class="pricing-tier {{ $isSelected ? 'active' : '' }} {{ $isLowestPrice ? 'best-value' : '' }}" 
                                                                 data-food-id="{{ $food['id'] }}" 
                                                                 data-size-id="{{ $size['id'] }}" 
                                                                 data-price="{{ $size['price'] }}"
                                                                 data-meal-id="{{ $selectedMeal->id ?? '' }}">
                                                                @if($isLowestPrice)
                                                                    <div class="best-value-badge">Best Value</div>
                                                                @endif
                                                                <div class="tier-content">
                                                                    <div class="tier-image-container">
                                                                        <img src="{{ $size['image'] ?? 'https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' }}" 
                                                                             alt="{{ $size['name'] }}" class="tier-image">
                                                                    </div>
                                                                    <div class="tier-text">
                                                                        <div class="tier-title">{{ $size['name'] }}</div>
                                                                        <div class="tier-price">₦{{ number_format($size['price'], 2) }}</div>
                                                                        <div class="tier-quantity">
                                                                            <label class="form-label small mb-1">Qty:</label>
                                                                            <div class="input-group input-group-sm">
                                                                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                                                                        wire:click="updateFoodSizeQuantity({{ $food['id'] }}, {{ $size['id'] }}, {{ max(0, $quantity - 1) }})">-</button>
                                                                                <input type="number" class="form-control form-control-sm text-center" 
                                                                                       value="{{ $quantity }}" 
                                                                                       min="0" 
                                                                                       wire:model.live="selectedSizes.{{ $food['id'] }}.{{ $size['id'] }}"
                                                                                       wire:change="updateFoodSizeQuantity({{ $food['id'] }}, {{ $size['id'] }}, $event.target.value)"
                                                                                       data-food-id="{{ $food['id'] }}" 
                                                                                       data-size-id="{{ $size['id'] }}"
                                                                                       data-meal-id="{{ $selectedMeal->id ?? '' }}">
                                                                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                                                                        wire:click="updateFoodSizeQuantity({{ $food['id'] }}, {{ $size['id'] }}, {{ $quantity + 1 }})">+</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center h-100">
                                                    <div class="text-center w-100">
                                                        <small class="text-muted d-block mb-2">Total for this food:</small>
                                                        <div class="h5 text-primary mb-0">
                                                            ₦{{ number_format($food['total_price'] ?? 0, 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    </div>
                                @endforeach
                                    </div>
                                </div>
                            </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" data-bs-dismiss="modal" id="addToCartBtn" wire:click="addToCart()">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart - <span id="modalTotalPrice">₦{{ number_format($modalTotal, 2) }}</span>
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
                    <h5 class="modal-title">Meal Preparation Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="video-container">
                        @if($modalVideoUrl)
                            <iframe id="videoIframe" src="{{ $modalVideoUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <div class="text-center p-5">
                                <i class="fas fa-video text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No video available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const mealCardsForFilter = document.querySelectorAll('.col-lg-4[data-category]');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter meal cards
            mealCardsForFilter.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Set up when Livewire is ready
    document.addEventListener('livewire:init', () => {
        console.log('Livewire initialized');
    });
});
</script>
@endpush

@push('styles')
<style>
.info-btn {
    width: 60px;
    height: 60px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    cursor: pointer;
}

/* Modal image styles */
#modalMealImage,
#modalVideoThumbnail img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 8px;
}

#modalVideoThumbnail {
    width: 100%;
    height: 300px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease;
}

#modalVideoThumbnail:hover {
    transform: scale(1.02);
}

/* Video modal styles */
.video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.food-item {
    background-color: #f8f9fa;
}

.food-sizes-container {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    scrollbar-width: thin;
    scrollbar-color: #ccc transparent;
}

.food-sizes-container::-webkit-scrollbar {
    height: 6px;
}

.food-sizes-container::-webkit-scrollbar-track {
    background: transparent;
}

.food-sizes-container::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

.food-sizes-container::-webkit-scrollbar-thumb:hover {
    background: #999;
}

.food-sizes-scroll {
    display: flex;
    gap: 12px;
    padding: 4px 0;
    min-width: max-content;
}

.food-sizes .pricing-tier {
    flex: 0 0 200px;
    width: 200px;
    height: 120px;
    text-align: left;
    padding: 12px;
    border: 2px solid #eee;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    position: relative;
    overflow: hidden;
}

.food-sizes .pricing-tier:hover,
.food-sizes .pricing-tier.active {
    border-color: var(--primary);
    background: rgba(212, 167, 106, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.food-sizes .pricing-tier.active {
    background: rgba(212, 167, 106, 0.2);
    border-color: var(--primary);
}

.tier-content {
    display: flex;
    height: 100%;
    gap: 12px;
}

.tier-image-container {
    flex: 0 0 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.food-sizes .tier-image {
    width: 100%;
    height: 100%;
    border-radius: 6px;
    object-fit: cover;
}

.tier-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.food-sizes .tier-title {
    font-weight: 400;
    margin-bottom: 4px;
    font-size: 0.75rem;
    line-height: 1.2;
    color: #666;
}

.food-sizes .tier-price {
    color: var(--primary);
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.tier-quantity {
    margin-top: auto;
}

.tier-quantity .form-label {
    font-size: 0.7rem;
    margin-bottom: 4px;
}

.tier-quantity .input-group {
    max-width: 100%;
}

.tier-quantity .input-group .form-control {
    text-align: center;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
}

.tier-quantity .input-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.food-item .input-group {
    max-width: 150px;
}

.food-item .input-group .form-control {
    text-align: center;
    font-weight: 600;
}

.food-item .input-group .btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.food-sizes .pricing-tier.best-value {
    border-color: #28a745;
    background: rgba(40, 167, 69, 0.05);
}

.food-sizes .pricing-tier.best-value .tier-price {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.best-value-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #28a745;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 600;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .food-item .row {
        flex-direction: column;
    }
    
    .food-item .col-md-4,
    .food-item .col-md-5,
    .food-item .col-md-3 {
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .food-sizes-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .food-sizes .pricing-tier {
        flex: 0 0 180px;
        width: 180px;
        height: 110px;
        min-width: 180px;
    }
    
    .tier-content {
        gap: 8px;
    }
    
    .tier-quantity .input-group {
        max-width: 100%;
    }
    
    .tier-quantity .input-group .form-control {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
    }
    
    .tier-quantity .input-group .btn {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    
    #modalMealImage,
    #modalVideoThumbnail img {
        height: 200px;
    }
    
    #modalVideoThumbnail {
        height: 200px;
    }
}
</style>
@endpush