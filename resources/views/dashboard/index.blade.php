@extends('layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-lg-8 mb-4 order-0">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary">{{ __('Welcome to Apex Property Management') }} 🎉</h5>
              <p class="mb-4">
                {{ __('You have') }} <span class="fw-bold">{{ $propertiesCount }}</span> {{ __('properties,') }}
                <span class="fw-bold">{{ $unitsCount }}</span> {{ __('units,') }}
                <span class="fw-bold">{{ $leasesCount }}</span> {{ __('active leases, and') }}
                <span class="fw-bold">{{ $maintenanceCount }}</span> {{ __('maintenance requests.') }}
              </p>

              <a href="{{ route('properties.index') }}" class="btn btn-sm btn-label-primary">{{ __('Manage Properties') }}</a>
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img
                src="/assets/img/illustrations/man-with-laptop-light.png"
                height="140"
                alt="Property Management"
                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                data-app-light-img="illustrations/man-with-laptop-light.png"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body pb-0">
              <span class="d-block fw-semibold mb-1">{{ __('Properties') }}</span>
              <h3 class="card-title mb-1">{{ $propertiesCount }}</h3>
            </div>
            <div id="propertiesChart" class="mb-3"></div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-building bx-lg"></i>
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="propertiesOpt"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="propertiesOpt">
                    <a class="dropdown-item" href="{{ route('properties.index') }}">{{ __('View All') }}</a>
                    <a class="dropdown-item" href="{{ route('properties.create') }}">{{ __('Add New') }}</a>
                  </div>
                </div>
              </div>
              <span>{{ __('Total Properties') }}</span>
              <h3 class="card-title text-nowrap mb-1">{{ $propertiesCount }}</h3>
              <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> {{ __('Active') }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Statistics Cards -->
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
      <div class="row">
        <div class="col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-door-open bx-lg"></i>
                </div>
              </div>
              <span>{{ __('Units') }}</span>
              <h3 class="card-title text-nowrap mb-2">{{ $unitsCount }}</h3>
              <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> {{ __('Available') }}</small>
            </div>
          </div>
        </div>
        <div class="col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-file bx-lg"></i>
                </div>
              </div>
              <span>{{ __('Leases') }}</span>
              <h3 class="card-title text-nowrap mb-2">{{ $leasesCount }}</h3>
              <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> {{ __('Active') }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
      <div class="row">
        <div class="col-12 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                  <div class="card-title">
                    <h5 class="text-nowrap mb-2">{{ __('Maintenance Overview') }}</h5>
                    <span class="badge bg-label-warning rounded-pill">{{ __('This Month') }}</span>
                  </div>
                  <div class="mt-sm-auto">
                    <small class="text-success text-nowrap fw-semibold"
                      ><i class="bx bx-chevron-up"></i> {{ __('Requests') }}</small
                    >
                    <h3 class="mb-0">{{ $maintenanceCount }}</h3>
                  </div>
                </div>
                <div id="maintenanceChart"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Recent Activity -->
    <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
          <div class="card-title mb-0">
            <h5 class="m-0 me-2">{{ __('Recent Activity') }}</h5>
            <small class="text-muted">{{ __('Latest updates') }}</small>
          </div>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="bx bx-building"></i>
                </span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">{{ __('New Property Added') }}</h6>
                  <small class="text-muted">{{ __('Property management updated') }}</small>
                </div>
              </div>
            </li>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-door-open"></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">{{ __('Unit Rented') }}</h6>
                  <small class="text-muted">{{ __('Lease agreement signed') }}</small>
                </div>
              </div>
            </li>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-wrench"></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">{{ __('Maintenance Request') }}</h6>
                  <small class="text-muted">{{ __('New maintenance issue reported') }}</small>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6 col-lg-4 order-1 mb-4">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title m-0 me-2">{{ __('Quick Actions') }}</h5>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <a href="{{ route('properties.create') }}" class="btn btn-primary">
              <i class="bx bx-plus me-2"></i>{{ __('Add Property') }}
            </a>
            <a href="{{ route('units.create') }}" class="btn btn-success">
              <i class="bx bx-plus me-2"></i>{{ __('Add Unit') }}
            </a>
            <a href="{{ route('leases.create') }}" class="btn btn-info">
              <i class="bx bx-plus me-2"></i>{{ __('Create Lease') }}
            </a>
            <a href="{{ route('maintenance.create') }}" class="btn btn-warning">
              <i class="bx bx-plus me-2"></i>{{ __('Report Issue') }}
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- System Status -->
    <div class="col-md-6 col-lg-4 order-2 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">{{ __('System Status') }}</h5>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column align-items-center gap-1">
              <h4 class="mb-2">{{ __('Online') }}</h4>
              <span>{{ __('All Systems Operational') }}</span>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <div class="d-flex align-items-center">
                <div class="badge bg-success rounded-pill p-1 me-2"></div>
                <span class="text-sm">{{ __('Database') }}</span>
              </div>
            </div>
            <div class="col-6">
              <div class="d-flex align-items-center">
                <div class="badge bg-success rounded-pill p-1 me-2"></div>
                <span class="text-sm">{{ __('API') }}</span>
              </div>
            </div>
            <div class="col-6">
              <div class="d-flex align-items-center">
                <div class="badge bg-success rounded-pill p-1 me-2"></div>
                <span class="text-sm">{{ __('Storage') }}</span>
              </div>
            </div>
            <div class="col-6">
              <div class="d-flex align-items-center">
                <div class="badge bg-success rounded-pill p-1 me-2"></div>
                <span class="text-sm">{{ __('Email') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
