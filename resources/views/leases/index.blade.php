@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Leases</h3>
            @can('manage leases')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeaseModal">Add Lease</button>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="leasesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Unit</th>
                    <th>Tenant</th>
                    <th>Landlord</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Rent Amount</th>
                    <th>Deposit Amount</th>
                    <th>Payment Frequency</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leases as $lease)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $lease->unit->unit_label ?? '-' }} ({{ $lease->unit->property->title ?? '-' }})</td>
                        <td>{{ $lease->tenant->name ?? '-' }}</td>
                        <td>{{ $lease->landlord->name ?? '-' }}</td>
                        <td>{{ $lease->start_date }}</td>
                        <td>{{ $lease->end_date }}</td>
                        <td>{{ number_format($lease->rent_amount, 2) }}</td>
                        <td>{{ number_format($lease->deposit_amount, 2) }}</td>
                        <td>{{ $lease->payment_frequency }}</td>
                        <td>{{ $lease->status }}</td>
                        <td>
                            @can('manage leases')
                                <button class="btn btn-sm btn-warning btn-edit-lease" data-id="{{ $lease->id }}"
                                    data-unit_id="{{ $lease->unit_id }}" data-tenant_id="{{ $lease->tenant_id }}"
                                    data-landlord_id="{{ $lease->landlord_id }}" data-start_date="{{ $lease->start_date }}"
                                    data-end_date="{{ $lease->end_date }}" data-rent_amount="{{ $lease->rent_amount }}"
                                    data-deposit_amount="{{ $lease->deposit_amount }}" data-payment_frequency="{{ $lease->payment_frequency }}"
                                    data-status="{{ $lease->status }}" data-lease_pdf_url="{{ $lease->lease_pdf_url }}"
                                    data-bs-toggle="modal" data-bs-target="#editLeaseModal">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger btn-delete-lease" data-id="{{ $lease->id }}"
                                    data-lease_id="{{ $lease->id }}" data-bs-toggle="modal"
                                    data-bs-target="#deleteLeaseModal">
                                    Delete
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('leases._create_modal')
    @include('leases._edit_modal')
    @include('leases._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#leasesTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-lease');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editLeaseModal');
                    modal.querySelector('form').action = '/leases/' + btn.dataset.id;
                    modal.querySelector('[name="unit_id"]').value = btn.dataset.unit_id;
                    modal.querySelector('[name="tenant_id"]').value = btn.dataset.tenant_id;
                    modal.querySelector('[name="landlord_id"]').value = btn.dataset.landlord_id;
                    modal.querySelector('[name="start_date"]').value = btn.dataset.start_date;
                    modal.querySelector('[name="end_date"]').value = btn.dataset.end_date;
                    modal.querySelector('[name="rent_amount"]').value = btn.dataset.rent_amount;
                    modal.querySelector('[name="deposit_amount"]').value = btn.dataset.deposit_amount;
                    modal.querySelector('[name="payment_frequency"]').value = btn.dataset.payment_frequency;
                    modal.querySelector('[name="status"]').value = btn.dataset.status;
                    modal.querySelector('[name="lease_pdf_url"]').value = btn.dataset.lease_pdf_url;
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-lease');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deleteLeaseModal');
                    modal.querySelector('form').action = '/leases/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = 'Lease #' + btn.dataset.lease_id;
                });
            });
        });
    </script>
@endsection