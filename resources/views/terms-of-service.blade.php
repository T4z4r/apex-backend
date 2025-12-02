@extends('layouts.guest')

@section('content')
    <div class="container mt-5">
        @php
            $policy = \App\Models\Policy::where('type', 'terms_of_service')->active()->first();
        @endphp

        @if($policy)
            <h1 class="mb-4">{{ $policy->title }}</h1>
            <p class="text-muted">Last updated: {{ $policy->updated_at->format('F j, Y') }}</p>

            <div class="policy-content">
                {!! $policy->content !!}
            </div>
        @else
            <h1 class="mb-4">Terms of Service</h1>
            <p>Terms of service content is being updated. Please check back later.</p>
        @endif

        <div class="mt-4">
            <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Back to Registration') }}</a>
        </div>
    </div>
@endsection
