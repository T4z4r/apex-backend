@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Roles') }}</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">{{ __('Add Role') }}</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="rolesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit-role" data-id="{{ $role->id }}"
                                data-name="{{ $role->name }}" data-permissions="{{ $role->permissions->pluck('id')->join(',') }}" data-bs-toggle="modal"
                                data-bs-target="#editRoleModal">
                                {{ __('Edit') }}
                            </button>

                            <button class="btn btn-sm btn-danger btn-delete-role" data-id="{{ $role->id }}"
                                data-name="{{ $role->name }}" data-bs-toggle="modal"
                                data-bs-target="#deleteRoleModal">
                                {{ __('Delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('roles._create_modal', ['permissions' => $permissions])
    @include('roles._edit_modal', ['permissions' => $permissions])
    @include('roles._delete_modal')
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#rolesTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-role');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editRoleModal');
                    modal.querySelector('form').action = '/roles/' + btn.dataset.id;
                    modal.querySelector('[name="name"]').value = btn.dataset.name;

                    // Handle permissions
                    const permissionsSelect = modal.querySelector('[name="permissions[]"]');
                    const selectedPermissions = btn.dataset.permissions ? btn.dataset.permissions.split(',') : [];

                    // Clear all selections
                    Array.from(permissionsSelect.options).forEach(option => {
                        option.selected = selectedPermissions.includes(option.value);
                    });
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-role');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deleteRoleModal');
                    modal.querySelector('form').action = '/roles/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.name;
                });
            });
        });
    </script>
@endsection