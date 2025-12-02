@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('View Policy') }}</h3>
            <div>
                <a href="{{ route('policies.edit', $policy->id) }}" class="btn btn-warning me-2">{{ __('Edit') }}</a>
                <a href="{{ route('policies.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $policy->title }}</h5>
                <small class="text-muted">{{ __('Type:') }} {{ ucfirst(str_replace('_', ' ', $policy->type)) }}</small>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>{{ __('Status:') }}</strong>
                    @if($policy->is_active)
                        <span class="badge bg-success">{{ __('Active') }}</span>
                    @else
                        <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>{{ __('Last Updated:') }}</strong>
                    {{ $policy->updated_at->format('F j, Y \a\t g:i A') }}
                </div>

                <div class="mb-3">
                    <strong>{{ __('Content:') }}</strong>
                </div>

                <div class="policy-content border rounded p-3 bg-light">
                    {!! $policy->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add some basic styling for the content
            const contentDiv = document.querySelector('.policy-content');
            if (contentDiv) {
                contentDiv.style.minHeight = '200px';
            }
        });
    </script>
@endsection