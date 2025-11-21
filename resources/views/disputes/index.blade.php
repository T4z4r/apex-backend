@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Disputes') }}</h3>
            @can('manage disputes')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDisputeModal">{{ __('Add Dispute') }}</button>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="disputesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Lease') }}</th>
                    <th>{{ __('Raised By') }}</th>
                    <th>{{ __('Issue') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($disputes as $dispute)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dispute->lease->unit->unit_label ?? '-' }} (Lease #{{ $dispute->lease_id }})</td>
                        <td>{{ $dispute->raisedBy->name ?? '-' }}</td>
                        <td>{{ Str::limit($dispute->issue, 50) }}</td>
                        <td>{{ $dispute->status }}</td>
                        <td>
                            @can('manage disputes')
                                <button class="btn btn-sm btn-warning btn-edit-dispute" data-id="{{ $dispute->id }}"
                                    data-lease_id="{{ $dispute->lease_id }}" data-raised_by="{{ $dispute->raised_by }}"
                                    data-issue="{{ $dispute->issue }}" data-status="{{ $dispute->status }}"
                                    data-evidence="{{ $dispute->evidence }}" data-admin_resolution_notes="{{ $dispute->admin_resolution_notes }}"
                                    data-bs-toggle="modal" data-bs-target="#editDisputeModal">
                                    {{ __('Edit') }}
                                </button>

                                <button class="btn btn-sm btn-danger btn-delete-dispute" data-id="{{ $dispute->id }}"
                                    data-issue="{{ Str::limit($dispute->issue, 20) }}" data-bs-toggle="modal"
                                    data-bs-target="#deleteDisputeModal">
                                    {{ __('Delete') }}
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('disputes._create_modal')
    @include('disputes._edit_modal')
    @include('disputes._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#disputesTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-dispute');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editDisputeModal');
                    modal.querySelector('form').action = '/disputes/' + btn.dataset.id;
                    modal.querySelector('[name="lease_id"]').value = btn.dataset.lease_id;
                    modal.querySelector('[name="raised_by"]').value = btn.dataset.raised_by;
                    modal.querySelector('[name="issue"]').value = btn.dataset.issue;
                    modal.querySelector('[name="status"]').value = btn.dataset.status;
                    modal.querySelector('[name="evidence"]').value = btn.dataset.evidence;
                    modal.querySelector('[name="admin_resolution_notes"]').value = btn.dataset.admin_resolution_notes;
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-dispute');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deleteDisputeModal');
                    modal.querySelector('form').action = '/disputes/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.issue;
                });
            });
        });
    </script>
@endsection
