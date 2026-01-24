<div>
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">Staff Settings</h1>
                <p class="text-muted mb-0">Manage your staff</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="ri-team-line me-2"></i>User Management
            </h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="ri-user-add-line me-1"></i>Add New User
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>2FA</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->image }}" alt="{{ $user->name }}"
                                        class="rounded-circle me-2" width="32" height="32">
                                    <span>{{ $user->name }}</span>
                                    @if($user->id === Auth::id())
                                    <span class="badge bg-info ms-2">You</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $user->two_factor_enabled ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($user->id !== Auth::id())
                                    <button type="button"
                                        class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                        wire:click="toggleUserStatus({{ $user->id }})"
                                        title="{{ $user->is_active ? 'Disable' : 'Enable' }} User">
                                        <i class="mdi {{ $user->is_active ? 'mdi-account-off-outline' : 'mdi-account-plus-outline' }}"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-danger"
                                        wire:click="deleteUser({{ $user->id }})"
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                        title="Delete User">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @else
                                    <span class="text-muted">No actions</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="ri-user-line text-2xl mb-2"></i>
                                <p>No users found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="addUserModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-user-add-line me-2"></i>Add New User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="addNewUser">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newUserName" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="newUserName"
                                wire:model="newUserName" required>
                            @error('newUserName') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="newUserEmail" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="newUserEmail"
                                wire:model="newUserEmail" required>
                            @error('newUserEmail') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="newUserPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="newUserPhone"
                                wire:model="newUserPhone">
                            @error('newUserPhone') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            A random password will be generated and sent to the user's email address.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-user-add-line me-1"></i>Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>