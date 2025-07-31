<div class="content-wrapper">
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Edit Blog Post: {{ $post->title }}</h3>
<div>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Posts
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="update" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Title -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Title <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa fa-pencil"></i></span>
                                <input type="text" wire:model="title" class="form-control" required placeholder="Enter post title">
                            </div>
                            @error('title') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Excerpt -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Excerpt <span class="text-danger">*</span></label>
                            <textarea wire:model="excerpt" class="form-control" rows="3" required placeholder="Brief description of the post"></textarea>
                            @error('excerpt') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Content <span class="text-danger">*</span></label>
                            @livewire('dashboard-area.partials.summernote-editor', [
                                'content' => $content,
                                'placeholder' => ' Post Content',
                                'wireModel' => 'content'
                            ])
                            @error('content')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SEO Section -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0"><i class="fa fa-search me-2"></i>SEO Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-0">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" wire:model="meta_keywords" class="form-control" placeholder="keyword1, keyword2, keyword3">
                                    @error('meta_keywords') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Featured Image -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Featured Image</label>
                            <input type="file" wire:model="featured_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <small class="text-muted">Recommended: 1200x420px</small>
                            
                            @if($currentFeaturedImage)
                                <div class="mt-2">
                                    <small class="text-muted">Current image:</small>
                                    <img src="{{ Storage::url($currentFeaturedImage) }}" alt="Current" class="img-fluid rounded mt-1" style="max-height: 120px;">
                                </div>
                            @endif
                            
                            <div id="imagePreview" class="mt-2">
                                <!-- JS will show preview here -->
                            </div>
                            @error('featured_image') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Category <span class="text-danger">*</span></label>
                            <select wire:model="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Tags</label>
                            <input type="text" wire:model="tags" class="form-control" placeholder="tag1, tag2, tag3">
                            <small class="text-muted">Separate tags with commas</small>
                            @error('tags') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Status</label>
                            <select wire:model="status" class="form-control">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                            @error('status') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Publish Date -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Publish Date</label>
                            <input type="datetime-local" wire:model="published_at" class="form-control">
                            @error('published_at') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Reading Time -->
                        <div class="mb-4">
                            <label class="form-label font-semibold">Reading Time (minutes)</label>
                            <input type="number" wire:model="reading_time" class="form-control" min="1" placeholder="Auto-calculated">
                            <small class="text-muted">Leave empty for auto-calculation</small>
                            @error('reading_time') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Checkboxes -->
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input type="checkbox" wire:model="featured" class="form-check-input ms-0" value="1">
                                <label class="form-check-label">Featured Post</label>
                            </div>
                            @error('featured') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" wire:model="allow_comments" class="form-check-input ms-0" value="1">
                                <label class="form-check-label">Allow Comments</label>
                            </div>
                            @error('allow_comments') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Submit Button -->
                        <button class="btn btn-primary w-100" type="submit" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fa fa-save me-2"></i>Update Post
                            </span>
                            <span wire:loading>
                                <i class="fa fa-spinner fa-spin me-2"></i>Updating...
                            </span>
                        </button>

                        <!-- Current Post Info -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">Current Post Info</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Category:</strong> 
                                    @if($post->category)
                                        <span class="badge bg-secondary">{{ $post->category->name }}</span>
                                    @else
                                        <span class="text-muted">No Category</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <strong>Status:</strong> 
                                    @if($post->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @elseif($post->status === 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-secondary">Archived</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <strong>Views:</strong> 
                                    <span class="badge bg-info">{{ $post->views_count }} views</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Comments:</strong> 
                                    <span class="badge bg-primary">{{ $post->comments_count ?? 0 }} comments</span>
                                </div>
                                @if($post->published_at)
                                    <div class="mb-2">
                                        <strong>Published:</strong> 
                                        <small class="text-muted">{{ $post->published_at->format('M d, Y H:i') }}</small>
                                    </div>
                                @endif
                                <div class="mb-0">
                                    <strong>Created:</strong> 
                                    <small class="text-muted">{{ $post->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('vendors/summernote/summernote-bs5.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('vendors/summernote/summernote-bs5.js') }}"></script>
<script>
    function uploadImage(file, editor) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $(editor).summernote('insertImage', e.target.result);
        }
        reader.readAsDataURL(file);
    }

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-fluid rounded shadow border mt-2';
                img.style.maxHeight = '180px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
