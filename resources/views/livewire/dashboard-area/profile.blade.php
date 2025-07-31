<div class="content-wrapper">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">Profile Settings</h1>
                <p class="text-muted mb-0">Manage your account information, security, and preferences</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Settings Menu</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <button wire:click="setActiveSection('basic')"
                            class="list-group-item list-group-item-action d-flex align-items-center {{ $activeSection === 'basic' ? 'active' : '' }}">
                            <i class="ri-user-line me-3"></i>
                            <span>Basic Information</span>
                        </button>
                        <button wire:click="setActiveSection('password')"
                            class="list-group-item list-group-item-action d-flex align-items-center {{ $activeSection === 'password' ? 'active' : '' }}">
                            <i class="ri-lock-password-line me-3"></i>
                            <span>Security</span>
                        </button>
                        <button wire:click="setActiveSection('users')"
                            class="list-group-item list-group-item-action d-flex align-items-center {{ $activeSection === 'users' ? 'active' : '' }}">
                            <i class="ri-team-line me-3"></i>
                            <span>User Management</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Basic Information Section -->
            @if($activeSection === 'basic')
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-line me-2"></i>Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updateBasicInfo">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name"
                                    wire:model="name" required>
                                @error('name') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email"
                                    wire:model="email" required>
                                @error('email') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone"
                                    wire:model="phone">
                                @error('phone') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Two-Factor Authentication</label>
                                <div class="d-flex align-items-center">
                                    <span class="badge {{ $two_factor_enabled ? 'bg-success' : 'bg-secondary' }} me-3">
                                        {{ $two_factor_enabled ? 'Enabled' : 'Disabled' }}
                                    </span>
                                    <button type="button"
                                        wire:click="toggleTwoFactor"
                                        class="btn btn-sm {{ $two_factor_enabled ? 'btn-danger' : 'btn-primary' }}">
                                        <i class="ri-{{ $two_factor_enabled ? 'close' : 'shield-check' }}-line me-1"></i>
                                        {{ $two_factor_enabled ? 'Disable 2FA' : 'Enable 2FA' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Update Information
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Security Section -->
            @if($activeSection === 'password')
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-lock-password-line me-2"></i>Security
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updatePassword">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="current_password" class="form-label">Current Password *</label>
                                <input type="password" class="form-control" id="current_password"
                                    wire:model="current_password" required>
                                @error('current_password') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">New Password *</label>
                                <input type="password" class="form-control" id="new_password"
                                    wire:model="new_password" required>
                                @error('new_password') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                                <input type="password" class="form-control" id="new_password_confirmation"
                                    wire:model="new_password_confirmation" required>
                                @error('new_password_confirmation') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-lock-password-line me-1"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- User Management Section -->
            @if($activeSection === 'users')
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
                                                <i class="ri-{{ $user->is_active ? 'user-unfollow' : 'user-follow' }}-line"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-danger"
                                                wire:click="deleteUser({{ $user->id }})"
                                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                                title="Delete User">
                                                <i class="ri-delete-bin-line"></i>
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
            @endif
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="addUserModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-user-add-line me-2"></i>Add New User
                    </h5>
                    <button type="button" class="btn-close" wire:click="hideAddUserModal"></button>
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
                        <button type="button" class="btn btn-secondary" wire:click="hideAddUserModal">
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


@push('styles')
<style>
    .list-group-item {
        border: none;
        border-radius: 0;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        background-color: var(--bs-primary-bg-subtle);
        color: var(--bs-primary);
    }

    .list-group-item.active {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
    }

    .list-group-item.active i {
        color: white;
    }

    .list-group-item:not(.active) i {
        color: var(--bs-secondary);
    }

    .list-group-item.text-danger:hover {
        background-color: var(--bs-danger-bg-subtle);
        color: var(--bs-danger);
    }

    .list-group-item.text-danger.active {
        background-color: var(--bs-danger);
        border-color: var(--bs-danger);
    }

    .modal.show {
        background-color: rgba(0, 0, 0, 0.5);
    }
</style>
@endpush