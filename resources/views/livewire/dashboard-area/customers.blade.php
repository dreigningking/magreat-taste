<div>
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Customers</h6>
        </div>
        <div class="card-body pt-0">
          <div class="row">
            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0">
              <div class="card">
                <div class="card-body p-3">
                  <div class="d-flex">
                    <div>
                      <div class="icon icon-shape bg-gradient-primary text-center border-radius-md">
                        <i class="fa fa-users text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                    <div class="ms-3">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Customers</p>
                        <h5 class="font-weight-bolder mb-0">
                          {{ number_format($stats['customers']) }}
                        </h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0">
              <div class="card">
                <div class="card-body p-3">
                  <div class="d-flex">
                    <div>
                      <div class="icon icon-shape bg-gradient-success text-center border-radius-md">
                        <i class="fa fa-globe text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                    <div class="ms-3">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-capitalize font-weight-bold">States</p>
                        <h5 class="font-weight-bolder mb-0">
                          {{ number_format($stats['states']) }}
                        </h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0">
              <div class="card">
                <div class="card-body p-3">
                  <div class="d-flex">
                    <div>
                      <div class="icon icon-shape bg-gradient-info text-center border-radius-md">
                        <i class="fa fa-map text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                    <div class="ms-3">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Cities</p>
                        <h5 class="font-weight-bolder mb-0">
                          {{ number_format($stats['cities']) }}
                        </h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0">
                <div class="card">
                <div class="card-body p-3">
                  <div class="d-flex">
                    <div>
                      <div class="icon icon-shape bg-gradient-warning text-center border-radius-md">
                        <i class="fa fa-building text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                    <div class="ms-3">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Places</p>
                        <h5 class="font-weight-bolder mb-0">
                          {{ number_format($stats['places']) }}
                        </h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Address</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Orders</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Spent</th>
                  <th class="text-secondary opacity-7">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($customers as $customer)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <div class="avatar avatar-sm me-3 bg-gradient-primary text-center border-radius-md d-flex align-items-center justify-content-center">
                          <i class="ni ni-single-02 text-white text-sm"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $customer->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $customer->email ?? 'No email' }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $customer->phone }}</p>
                    <p class="text-xs text-secondary mb-0">Phone</p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <span class="text-xs">{{ Str::limit($customer->address, 30) }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="badge badge-sm bg-gradient-info">{{ $customer->order_count }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold">₦{{ number_format($customer->total_spent, 2) }}</span>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex">
                      @if($customer->email)
                      <a href="mailto:{{ $customer->email }}" class="btn btn-link text-primary mb-0 me-2" title="Send Email">
                        <i class="fa fa-envelope"></i>
                      </a>
                      @endif
                      <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" target="_blank" class="btn btn-link text-success mb-0" title="WhatsApp Chat">
                        <i class="fab fa-whatsapp"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center py-4">
                    <i class="ni ni-single-02 text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No customers found</p>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($customers->hasPages())
          <div class="d-flex justify-content-center mt-4">
              {{ $customers->links('vendor.pagination.bootstrap-5') }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>