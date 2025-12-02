@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Create Policy') }}</h3>
            <a href="{{ route('policies.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('policies.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="type" class="form-label">{{ __('Type') }}</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="">{{ __('Select Policy Type') }}</option>
                            <option value="privacy_policy">{{ __('Privacy Policy') }}</option>
                            <option value="terms_of_service">{{ __('Terms of Service') }}</option>
                        </select>
                        @error('type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('Title') }}</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">{{ __('Content') }}</label>
                        <textarea class="form-control" id="content" name="content" rows="20" required>{{ old('content') }}</textarea>
                        @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Create Policy') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor if available
            if (typeof ClassicEditor !== 'undefined') {
                ClassicEditor
                    .create(document.querySelector('#content'))
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    </script>
@endsection