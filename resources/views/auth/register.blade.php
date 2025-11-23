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

                <!-- Role -->
                <div class="mb-3">
                  <label for="role" class="form-label">{{ __('I am registering as') }}</label>
                  <select id="role" name="role" class="form-select" required>
                    <option value="">{{ __('Select your role') }}</option>
                    <option value="landlord" {{ old('role') == 'landlord' ? 'selected' : '' }}>{{ __('Landlord') }}</option>
                    <option value="tenant" {{ old('role') == 'tenant' ? 'selected' : '' }}>{{ __('Tenant') }}</option>
                    <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>{{ __('Agent') }}</option>
                  </select>
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
                      {{ __('I agree to') }} <a href="#">{{ __('privacy policy & terms') }}</a>
                    </label>
                  </div>
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
            const roleSelect = document.getElementById('role');
            const planContainer = document.getElementById('plan-selection');
            const planSelect = document.getElementById('plan_id');

            function togglePlanSelection() {
                if (roleSelect.value === 'landlord') {
                    planContainer.style.display = 'block';
                    planSelect.required = true;
                } else {
                    planContainer.style.display = 'none';
                    planSelect.required = false;
                    planSelect.value = '';
                }
            }

            roleSelect.addEventListener('change', togglePlanSelection);

            togglePlanSelection();
        });
    </script>
  </body>
</html>
