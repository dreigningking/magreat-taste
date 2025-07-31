<div>
    <textarea 
        id="{{ $uniqueId }}" 
        class="summernote-editor form-control" 
        rows="3" 
        placeholder="{{ $placeholder }}"
        wire:ignore
    >{{ $content }}</textarea>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    // Initialize Summernote for this specific instance
    $('#{{ $uniqueId }}').summernote({
        height: '{{ $height }}',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear','fontsize','italic']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']]
        ],
        callbacks: {
            onChange: function(contents, $editable) {
                // Dispatch event to parent component
                Livewire.dispatch('summernoteContentUpdated', { 
                    content: contents, 
                    wireModel: '{{ $wireModel }}',
                    editorId: '{{ $uniqueId }}'
                });
            }
        }
    });
});
</script>
@endpush 