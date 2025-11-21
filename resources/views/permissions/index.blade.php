@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Permissions') }}</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPermissionModal">{{ __('Add Permission') }}</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="permissionsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit-permission" data-id="{{ $permission->id }}"
                                data-name="{{ $permission->name }}" data-bs-toggle="modal"
                                data-bs-target="#editPermissionModal">
                                {{ __('Edit') }}
                            </button>

                            <button class="btn btn-sm btn-danger btn-delete-permission" data-id="{{ $permission->id }}"
                                data-name="{{ $permission->name }}" data-bs-toggle="modal"
                                data-bs-target="#deletePermissionModal">
                                {{ __('Delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('permissions._create_modal')
    @include('permissions._edit_modal')
    @include('permissions._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#permissionsTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-permission');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editPermissionModal');
                    modal.querySelector('form').action = '/permissions/' + btn.dataset.id;
                    modal.querySelector('[name="name"]').value = btn.dataset.name;
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-permission');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deletePermissionModal');
                    modal.querySelector('form').action = '/permissions/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.name;
                });
            });
        });
    </script>
@endsection