@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Policies') }}</h3>
            <a href="{{ route('policies.create') }}" class="btn btn-primary">{{ __('Add Policy') }}</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="policiesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($policies as $policy)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $policy->type)) }}</td>
                        <td>{{ $policy->title }}</td>
                        <td>
                            @if($policy->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('policies.edit', $policy->id) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                            <a href="{{ route('policies.show', $policy->id) }}" class="btn btn-sm btn-info">{{ __('View') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.jQuery && $.fn.DataTable) {
                $('#policiesTable').DataTable();
            }
        });
    </script>
@endsection