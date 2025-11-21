@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Users') }}</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">{{ __('Add User') }}</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="usersTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit-user"
                                data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}" data-phone="{{ $user->phone }}"
                                data-role="{{ $user->roles->pluck('name')->first() }}"
                                data-bs-toggle="modal" data-bs-target="#editUserModal">
                                {{ __('Edit') }}
                            </button>

                            <button class="btn btn-sm btn-danger btn-delete-user" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-bs-toggle="modal"
                                data-bs-target="#deleteUserModal">
                                {{ __('Delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('users._create_modal')
    @include('users._edit_modal')
    @include('users._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#usersTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-user');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editUserModal');
                    modal.querySelector('form').action = '/users/' + btn.dataset.id;
                    modal.querySelector('[name="name"]').value = btn.dataset.name;
                    modal.querySelector('[name="email"]').value = btn.dataset.email;
                    modal.querySelector('[name="phone"]').value = btn.dataset.phone;
                    modal.querySelector('[name="role"]').value = btn.dataset.role;
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-user');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deleteUserModal');
                    modal.querySelector('form').action = '/users/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.name;
                });
            });
        });
    </script>
@endsection