<!DOCTYPE html>

<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets') }}/"
  data-template="vertical-menu-template"
>
    <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Register - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper1 authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card mx-auto" style="max-width: 800px !important; width: 100%  !important;">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="{{ url('/') }}" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <!-- Logo SVG kept same -->
                  </span>
                  <span class="app-brand-text demo text-body fw-bolder">{{ config('app.name', 'Apex') }}</span>
                </a>
              </div>
              <!-- /Logo -->

              <h4 class="mb-2">{{ __('Adventure starts here 🚀') }}</h4>
              <p class="mb-4">{{ __('Make your app management easy and fun!') }}</p>

              <form id="formAuthentication" class="mb-3" action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Name and Email Row -->
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ __('Enter your full name') }}" autofocus/>
                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="text" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ __('Enter your email') }}"/>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                  </div>
                </div>

                <!-- Password and Confirm Password Row -->
                <div class="row">
                  <div class="col-md-6 mb-3 form-password-toggle">
                    <label class="form-label" for="password">{{ __('Password') }}</label>
                    <div class="input-group input-group-merge">
                      <input type="password" id="password" name="password" class="form-control" placeholder="************"/>
                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-6 mb-3 form-password-toggle">
                    <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                    <div class="input-group input-group-merge">
                      <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="************"/>
                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                    @error('password_confirmation') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                  </div>
                </div>

                <!-- Role Selection -->
                <div class="mb-3">
                  <label class="form-label">{{ __('I am registering as') }}</label>
                  <div class="row">
                    <div class="col-md mb-md-0 mb-2">
                      <div class="form-check custom-option custom-option-icon">
                        <label class="form-check-label custom-option-content" for="customRadioLandlord">
                          <span class="custom-option-body">
                            <i class="bx bx-building-house"></i>
                            <span class="custom-option-title">{{ __('Landlord') }}</span>
                            <small>{{ __('Manage properties, collect rent, and oversee your real estate portfolio.') }}</small>
                          </span>
                          <input
                            name="role"
                            class="form-check-input"
                            type="radio"
                            value="landlord"
                            id="customRadioLandlord"
                            {{ old('role') == 'landlord' ? 'checked' : '' }}
                            required
                          />
                        </label>
                      </div>
                    </div>
                    <div class="col-md mb-md-0 mb-2">
                      <div class="form-check custom-option custom-option-icon">
                        <label class="form-check-label custom-option-content" for="customRadioTenant">
                          <span class="custom-option-body">
                            <i class="bx bx-home"></i>
                            <span class="custom-option-title">{{ __('Tenant') }}</span>
                            <small>{{ __('Find and rent properties, manage leases, and track maintenance requests.') }}</small>
                          </span>
                          <input
                            name="role"
                            class="form-check-input"
                            type="radio"
                            value="tenant"
                            id="customRadioTenant"
                            {{ old('role') == 'tenant' ? 'checked' : '' }}
                          />
                        </label>
                      </div>
                    </div>
                    <div class="col-md mb-md-0 mb-2">
                      <div class="form-check custom-option custom-option-icon">
                        <label class="form-check-label custom-option-content" for="customRadioAgent">
                          <span class="custom-option-body">
                            <i class="bx bx-briefcase-alt"></i>
                            <span class="custom-option-title">{{ __('Agent') }}</span>
                            <small>{{ __('Help clients find properties and earn commissions on successful deals.') }}</small>
                          </span>
                          <input
                            name="role"
                            class="form-check-input"
                            type="radio"
                            value="agent"
                            id="customRadioAgent"
                            {{ old('role') == 'agent' ? 'checked' : '' }}
                          />
                        </label>
                      </div>
                    </div>
                  </div>
                  @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Plan Selection -->
                <div class="mb-3" id="plan-selection" style="display: none;">
                  <label for="plan_id" class="form-label">{{ __('Select a Plan') }}</label>
                  <select id="plan_id" name="plan_id" class="form-select">
                    <option value="">{{ __('Choose your plan') }}</option>
                    @foreach(\App\Models\Plan::where('is_active', true)->get() as $plan)
                      <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} - ${{ number_format($plan->monthly_price, 2) }}/month
                        ({{ $plan->max_properties == -1 ? 'Unlimited' : $plan->max_properties }} properties)
                      </option>
                    @endforeach
                  </select>
                  @error('plan_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Terms -->
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms"/>
                    <label class="form-check-label" for="terms-conditions">
                      {{ __('I agree to') }} <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">{{ __('privacy policy') }}</a> {{ __('and') }} <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">{{ __('terms of service') }}</a>
                    </label>
                  </div>
                  @error('terms') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary d-grid w-100">{{ __('Sign up') }}</button>
              </form>

              <p class="text-center">
                <span>{{ __('Already have an account?') }}</span>
                <a href="{{ route('login') }}"><span>{{ __('Sign in instead') }}</span></a>
              </p>



            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const planContainer = document.getElementById('plan-selection');
            const planSelect = document.getElementById('plan_id');

            function togglePlanSelection() {
                const selectedRole = document.querySelector('input[name="role"]:checked');
                if (selectedRole && selectedRole.value === 'landlord') {
                    planContainer.style.display = 'block';
                    planSelect.required = true;
                } else {
                    planContainer.style.display = 'none';
                    planSelect.required = false;
                    planSelect.value = '';
                }
            }

            function updateCheckedState() {
                // Remove checked class from all options
                document.querySelectorAll('.custom-option').forEach(option => {
                    option.classList.remove('checked');
                });

                // Add checked class to selected option
                const selectedRadio = document.querySelector('input[name="role"]:checked');
                if (selectedRadio) {
                    selectedRadio.closest('.custom-option').classList.add('checked');
                }
            }

            roleRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    togglePlanSelection();
                    updateCheckedState();
                });
            });

            togglePlanSelection();
            updateCheckedState();
        });
    </script>
    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @php
                $policy = \App\Models\Policy::where('type', 'privacy_policy')->active()->first();
            @endphp

            @if($policy)
                <p class="text-muted">Last updated: {{ $policy->updated_at->format('F j, Y') }}</p>

                <div class="policy-content">
                    {!! $policy->content !!}
                </div>
            @else
                <p>Privacy policy content is being updated. Please check back later.</p>
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Terms of Service Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="termsModalLabel">Terms of Service</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @php
                $policy = \App\Models\Policy::where('type', 'terms_of_service')->active()->first();
            @endphp

            @if($policy)
                <p class="text-muted">Last updated: {{ $policy->updated_at->format('F j, Y') }}</p>

                <div class="policy-content">
                    {!! $policy->content !!}
                </div>
            @else
                <p>Terms of service content is being updated. Please check back later.</p>
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
