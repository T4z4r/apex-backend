@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Maintenance Requests</h3>
            @if(auth()->user()->hasRole('tenant'))
                <a href="{{ route('maintenance.create') }}" class="btn btn-primary">Submit Request</a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="maintenanceTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Property</th>
                    <th>Unit</th>
                    <th>Tenant</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->unit->property->name ?? '-' }}</td>
                        <td>{{ $item->unit->unit_label ?? '-' }}</td>
                        <td>{{ $item->tenant->name ?? '-' }}</td>
                        <td>{{ $item->title }}</td>
                        <td>
                            <span class="badge bg-{{ $item->priority == 'urgent' ? 'danger' : ($item->priority == 'high' ? 'warning' : ($item->priority == 'medium' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($item->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'open' ? 'primary' : ($item->status == 'in_progress' ? 'info' : ($item->status == 'resolved' ? 'success' : 'danger')) }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td>{{ $item->assigned->name ?? '-' }}</td>
                        <td>
                            @if(!auth()->user()->hasRole('tenant'))
                                <a href="{{ route('maintenance.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                <button class="btn btn-sm btn-danger btn-delete-maintenance" data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-bs-toggle="modal" data-bs-target="#deleteMaintenanceModal">Delete</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('maintenance._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#maintenanceTable').DataTable();
            }

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-maintenance');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deleteMaintenanceModal');
                    modal.querySelector('form').action = '/maintenance/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.title;
                });
            });
        });
    </script>
@endsection
