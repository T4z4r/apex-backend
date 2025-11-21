@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Units</h3>
            @can('manage units')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUnitModal">Add Unit</button>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="unitsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Property</th>
                    <th>Unit Label</th>
                    <th>Bedrooms</th>
                    <th>Bathrooms</th>
                    <th>Rent</th>
                    <th>Deposit</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unit->property->name ?? '-' }}</td>
                        <td>{{ $unit->unit_label }}</td>
                        <td>{{ $unit->bedrooms ?? '-' }}</td>
                        <td>{{ $unit->bathrooms ?? '-' }}</td>
                        <td>{{ number_format($unit->rent_amount, 2) }}</td>
                        <td>{{ number_format($unit->deposit_amount ?? 0, 2) }}</td>
                        <td>{{ $unit->is_available ? 'Yes' : 'No' }}</td>
                        <td>
                            @can('manage units')
                                <button class="btn btn-sm btn-warning btn-edit-unit" data-id="{{ $unit->id }}"
                                    data-property_id="{{ $unit->property_id }}" data-unit_label="{{ $unit->unit_label }}"
                                    data-bedrooms="{{ $unit->bedrooms }}" data-bathrooms="{{ $unit->bathrooms }}"
                                    data-size_m2="{{ $unit->size_m2 }}" data-rent_amount="{{ $unit->rent_amount }}"
                                    data-deposit_amount="{{ $unit->deposit_amount }}"
                                    data-is_available="{{ $unit->is_available ? 1 : 0 }}" data-bs-toggle="modal"
                                    data-bs-target="#editUnitModal">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger btn-delete-unit" data-id="{{ $unit->id }}"
                                    data-unit_label="{{ $unit->unit_label }}" data-bs-toggle="modal"
                                    data-bs-target="#deleteUnitModal">
                                    Delete
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('units._create_modal')
    @include('units._edit_modal')
    @include('units._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#unitsTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-unit');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editUnitModal');
                    modal.querySelector('form').action = '/units/' + btn.dataset.id;
                    modal.querySelector('[name="unit_label"]').value = btn.dataset.unit_label;
                    modal.querySelector('[name="bedrooms"]').value = btn.dataset.bedrooms;
                    modal.querySelector('[name="bathrooms"]').value = btn.dataset.bathrooms;
                    modal.querySelector('[name="size_m2"]').value = btn.dataset.size_m2;
                    modal.querySelector('[name="rent_amount"]').value = btn.dataset.rent_amount;
                    modal.querySelector('[name="deposit_amount"]').value = btn.dataset
                        .deposit_amount;
                    modal.querySelector('[name="property_id"]').value = btn.dataset.property_id;
                    modal.querySelector('[name="is_available"]').checked = btn.dataset
                        .is_available == '1';
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-unit');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deleteUnitModal');
                    modal.querySelector('form').action = '/units/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.unit_label;
                });
            });
        });
    </script>
@endsection
