<div class="row">
  <div class="col-lg-3">
    <div class="card position-sticky top-1">
      <ul class="nav flex-column bg-white border-radius-lg p-3">

        <li class="nav-item pt-2">
          <a wire:click="setActiveSection('basic')" class="nav-link text-body {{ $activeSection === 'basic' ? 'active' : '' }}" data-scroll="" href="#basic-info">
            <div class="icon me-2">
              <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>document</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(154.000000, 300.000000)">
                        <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" opacity="0.603585379"></path>
                        <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="text-sm">Basic Info</span>
          </a>
        </li>

        <li class="nav-item pt-2">
          <a wire:click="setActiveSection('password')" class="nav-link text-body {{ $activeSection === 'password' ? 'active' : '' }}" data-scroll="" href="#password">
            <div class="icon me-2">
              <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>box-3d-50</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(603.000000, 0.000000)">
                        <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"></path>
                        <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                        <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="text-sm">Change Password</span>
          </a>
        </li>

      </ul>
    </div>
  </div>
  <div class="col-lg-9 mt-lg-0 mt-4">
    <!-- Card Profile -->
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
    @if($activeSection === 'basic')
    <!-- Card Basic Info -->
    <div class="card" id="basic-info">
      <div class="card-header">
        <h5>Basic Info</h5>
      </div>
      <form wire:submit.prevent="updateBasicInfo">
        <div class="card-body pt-0">
          <div class="row">
            <div class="col-md-6">
              <label for="name" class="form-label">Full Name *</label>
              <input type="text" class="form-control" id="name"
                wire:model="name" required onfocus="focused(this)" onfocusout="defocused(this)">
              @error('name') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email Address *</label>
              <input type="email" class="form-control" id="email"
                wire:model="email" required onfocus="focused(this)" onfocusout="defocused(this)">
              @error('email')
              <div class="text-danger text-xs mt-1">{{ $message }}</div>
              @enderror
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
              <div class="d-flex align-items-start">
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






        </div>
      </form>
    </div>
    @endif

    @if($activeSection === 'password')
    <!-- Card Change Password -->
    <div class="card" id="password">
      <div class="card-header">
        <h5>Change Password</h5>
      </div>
      <form wire:submit.prevent="updatePassword">
        <div class="card-body pt-0">
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
          <h5 class="mt-5">Password requirements</h5>
          <p class="text-muted mb-2">
            Please follow this guide for a strong password:
          </p>
          <ul class="text-muted ps-4 mb-0 float-start">
            <li>
              <span class="text-sm">One special characters</span>
            </li>
            <li>
              <span class="text-sm">Min 6 characters</span>
            </li>
            <li>
              <span class="text-sm">One number (2 are recommended)</span>
            </li>
            <li>
              <span class="text-sm">Change it often</span>
            </li>
          </ul>
          <button class="btn bg-gradient-dark btn-sm float-end mt-6 mb-0">Update password</button>
        </div>
      </form>
    </div>
    @endif
  </div>
</div>