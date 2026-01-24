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
                                alt="{{ $meal->name }}" class="menu-card-img" onerror="this.style.display='none'">
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
                                <img src="{{ $modalMealImage }}" alt="Video Thumbnail" class="img-fluid rounded w-100 h-100 object-fit-cover" onerror="this.style.display='none'">
                                <div class="play-btn position-absolute top-50 start-50 translate-middle">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="position-absolute bottom-0 start-0 bg-dark bg-opacity-75 text-white p-2 w-100">
                                    Watch Preparation Video
                                </div>
                            </div>
                            @else
                            <img id="modalMealImage" src="{{ $modalMealImage }}" alt="Meal Image" class="img-fluid rounded mb-3 w-100 h-100 object-fit-cover" onerror="this.style.display='none'">
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
                            <h5>Select Food Items</h5>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Select at least one food item. Required items are pre-selected and cannot be deselected.
                            </small>

                            <!-- Food Selection Checkboxes -->
                            <div class="food-selection-list mb-4 p-3 bg-light rounded">
                                <div class="row">
                                    @foreach($modalFoodItems as $food)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   id="food_select_{{ $food['id'] }}"
                                                   wire:model.live="selectedFoods.{{ $food['id'] }}"
                                                   {{ ($food['required'] ?? false) ? 'disabled checked' : '' }}>
                                            <label class="form-check-label" for="food_select_{{ $food['id'] }}">
                                                {{ $food['name'] }}
                                                @if($food['required'] ?? false)
                                                    <span class="badge bg-danger ms-1">Required</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Food Details - Only show for selected foods -->
                            <h5>Customize Sizes & Quantities</h5>
                            <div id="modalFoodItems">
                                @foreach($modalFoodItems as $food)
                                @if(isset($selectedFoods[$food['id']]) && $selectedFoods[$food['id']])
                                <div class="food-item mb-4 p-3 border rounded">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <div class="food-info">
                                                <h6 class="food-title mb-2">{{ $food['name'] }}</h6>
                                                <p class="food-description small text-muted mb-0">{{ $food['description'] }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="food-sizes-container">
                                                <div class="food-sizes-scroll">
                                                    @foreach($food['sizes'] as $size)
                                                    <div class="pricing-tier {{ $this->getSizeQuantity($food['id'], $size['id']) > 0 ? 'active' : '' }} {{ $this->isBestValueSize($food['id'], $size['id']) ? 'best-value' : '' }}"
                                                        data-food-id="{{ $food['id'] }}"
                                                        data-size-id="{{ $size['id'] }}"
                                                        data-price="{{ $size['price'] }}"
                                                        data-meal-id="{{ $selectedMeal->id ?? '' }}">
                                                        @if($this->isBestValueSize($food['id'], $size['id']))
                                                        <div class="best-value-badge">Best Value</div>
                                                        @endif
                                                        <div class="tier-content">
                                                            <div class="tier-image-container">
                                                                <img src="{{ $this->getSizeImageUrl($size) }}"
                                                                    alt="{{ $size['name'] }}" class="tier-image">
                                                                <div class="tier-title">{{ $size['name'] }}</div>
                                                            </div>
                                                            <div class="tier-text">
                                                                <div class="tier-price">{{ $this->formatPrice($size['price']) }}</div>
                                                                <div class="tier-quantity">
                                                                    <label class="form-label small mb-1">Qty:</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <button class="btn btn-outline-secondary btn-sm" type="button"
                                                                            wire:click="updateFoodSizeQuantity({{ $food['id'] }}, {{ $size['id'] }}, {{ max(0, $this->getSizeQuantity($food['id'], $size['id']) - 1) }})">-</button>
                                                                        <input type="number" class="form-control form-control-sm text-center"
                                                                            value="{{ $this->getSizeQuantity($food['id'], $size['id']) }}"
                                                                            min="0"
                                                                            wire:input="updateFoodSizeQuantity({{ $food['id'] }}, {{ $size['id'] }}, $event.target.value)"
                                                                            data-food-id="{{ $food['id'] }}"
                                                                            data-size-id="{{ $size['id'] }}"
                                                                            data-meal-id="{{ $selectedMeal->id ?? '' }}">
                                                                        <button class="btn btn-outline-secondary btn-sm" type="button"
                                                                            wire:click="updateFoodSizeQuantity({{ $food['id'] }}, {{ $size['id'] }}, {{ $this->getSizeQuantity($food['id'], $size['id']) + 1 }})">+</button>
                                                                    </div>
                                                                </div>
                                                                @if($this->getSizeQuantity($food['id'], $size['id']) > 0)
                                                                <div class="tier-remove">
                                                                    <button class="btn btn-outline-danger btn-sm w-100" type="button"
                                                                        wire:click="removeFoodSize({{ $food['id'] }}, {{ $size['id'] }})"
                                                                        {{ !$this->canRemoveSize($food['id'], $size['id']) ? 'disabled' : '' }}
                                                                        title="{{ !$this->canRemoveSize($food['id'], $size['id']) ? 'At least one size must be selected' : 'Remove this size' }}">
                                                                        {{ !$this->canRemoveSize($food['id'], $size['id']) ? 'Required' : 'Remove' }}
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="food-total text-end">
                                                <h6 class="mb-1">Total</h6>
                                                <p class="total-price mb-0">{{ $this->formatPrice($food['total_price']) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button class="btn btn-primary w-100 py-3" id="addToCartBtn" wire:click="addToCart()" {{ $this->canAddToCart() ? '' : 'disabled' }}>
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart - <span id="modalTotalPrice">{{ $this->formatPrice($modalTotal) }}</span>
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
    console.log('MenuSection script loaded');
    
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
        console.log('Livewire initialized in MenuSection');
        
        // Listen for Livewire events
        Livewire.on('closeModal', (data) => {
            console.log('closeModal event received', data);
            try {
                const modal = document.getElementById(data.modalId);
                if (modal) {
                    console.log('Closing modal:', data.modalId);
                    const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                    modalInstance.hide();
                } else {
                    console.error('Modal not found:', data.modalId);
                }
            } catch (error) {
                console.error('Error closing modal:', error);
            }
        });
    });
    
    // Debug quantity input changes
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[type="number"]')) {
            console.log('Quantity input changed:', {
                value: e.target.value,
                foodId: e.target.dataset.foodId,
                sizeId: e.target.dataset.sizeId,
                mealId: e.target.dataset.mealId
            });
        }
    });
    
    // Debug button clicks
    document.addEventListener('click', function(e) {
        if (e.target.matches('button[wire\\:click*="updateFoodSizeQuantity"]')) {
            console.log('Quantity button clicked:', e.target.getAttribute('wire:click'));
        }
        if (e.target.matches('button[wire\\:click*="addToCart"]')) {
            console.log('Add to cart button clicked');
        }
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
        padding-bottom: 56.25%;
        /* 16:9 aspect ratio */
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

    .food-item h6 {
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: 600;
    }

    .food-item .text-muted {
        font-size: 0.85rem;
        line-height: 1.4;
    }

    /* Compact food title on desktop */
    @media (min-width: 768px) {
        .food-item .col-md-4 h6 {
            margin-bottom: 0.25rem;
        }

        .food-item .col-md-4 p {
            margin-bottom: 0;
        }
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
        gap: 16px;
        padding: 4px 0;
        min-width: max-content;
    }

    /* Responsive sizing for different screen sizes */
    /* @media (min-width: 1200px) {
        .food-sizes-container {
            width: 90%;
        }
    }

    @media (min-width: 768px) and (max-width: 1199px) {
        .food-sizes-container {
            width: 90%;
        }
    } */

    @media (max-width: 767px) {
        .food-sizes-container {
            width: 100%;
        }
    }

    .food-sizes .pricing-tier {
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    .food-sizes .tier-image {
        width: 100%;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        margin-bottom: 8px;
    }

    .tier-title {
        font-weight: 500;
        font-size: 0.75rem;
        line-height: 1.2;
        color: #333;
        text-align: center;
        margin: 0;
    }

    .tier-text {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .food-sizes .tier-price {
        color: var(--primary);
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .tier-quantity {
        margin-bottom: 8px;
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

    .tier-remove {
        margin-top: auto;
    }

    .tier-remove .btn {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
        height: auto;
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

    .tier-remove .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }

    .tier-remove .btn:disabled:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }

    .food-item.selected {
        border-color: var(--primary) !important;
        background-color: rgba(212, 167, 106, 0.05) !important;
    }

    .food-item.unselected {
        opacity: 0.7;
    }

    .food-item.unselected .food-sizes-container {
        pointer-events: none;
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .food-item .row {
            flex-direction: column;
        }

        .food-item .col-md-2,
        .food-item .col-md-8 {
            width: 100%;
            margin-bottom: 1rem;
        }

        .food-sizes-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .food-sizes-scroll {
            gap: 12px;
        }

        .food-sizes .pricing-tier {
            width: 200px;
            height: 120px;
            min-width: 200px;
            flex-shrink: 0;
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

        .tier-remove .btn {
            font-size: 0.65rem;
            padding: 0.15rem 0.3rem;
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