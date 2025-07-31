<div>
@if($showModal && $task)
<div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5); z-index: 1050;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Task</h5>
                <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold mb-2">{{ $task->title }}</h6>
                <p class="text-muted mb-3">Please tell us what's wrong with this task:</p>
                <form wire:submit.prevent="submitReport">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                        <select id="reason" wire:model="reason" class="form-select">
                            <option value="">Select a reason</option>
                            <option value="broken_link">Broken Link</option>
                            <option value="unclear_instructions">Unclear Instructions</option>
                            <option value="takes_longer_than_2_hours">Takes Longer Than 2 Hours</option>
                            <option value="other">Other</option>
                        </select>
                        @error('reason') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Additional Details</label>
                        <textarea id="description" wire:model="description" rows="4" class="form-control" placeholder="Please provide more details about the issue..."></textarea>
                        @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" wire:click="closeModal" class="btn btn-outline-secondary flex-fill">Cancel</button>
                        <button type="submit" class="btn btn-danger flex-fill">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif 
</div>