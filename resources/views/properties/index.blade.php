@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Properties') }}</h3>
            @can('manage properties')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPropertyModal">{{ __('Add Property') }}</button>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="propertiesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Address') }}</th>
                    <th>{{ __('Neighborhood') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $property)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $property->title }}</td>
                        <td>{{ $property->description ?? '-' }}</td>
                        <td>{{ $property->address }}</td>
                        <td>{{ $property->neighborhood }}</td>
                        <td>
                            @can('manage properties')
                                <button class="btn btn-sm btn-warning btn-edit-property" data-id="{{ $property->id }}"
                                    data-title="{{ $property->title }}" data-description="{{ $property->description }}"
                                    data-address="{{ $property->address }}" data-neighborhood="{{ $property->neighborhood }}"
                                    data-geo_lat="{{ $property->geo_lat }}" data-geo_lng="{{ $property->geo_lng }}"
                                    data-amenities="{{ $property->amenities }}" data-bs-toggle="modal"
                                    data-bs-target="#editPropertyModal">
                                    {{ __('Edit') }}
                                </button>

                                <button class="btn btn-sm btn-danger btn-delete-property" data-id="{{ $property->id }}"
                                    data-title="{{ $property->title }}" data-bs-toggle="modal"
                                    data-bs-target="#deletePropertyModal">
                                    {{ __('Delete') }}
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('properties._create_modal')
    @include('properties._edit_modal')
    @include('properties._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#propertiesTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-property');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editPropertyModal');
                    modal.querySelector('form').action = '/properties/' + btn.dataset.id;
                    modal.querySelector('[name="title"]').value = btn.dataset.title;
                    modal.querySelector('[name="description"]').value = btn.dataset.description;
                    modal.querySelector('[name="address"]').value = btn.dataset.address;
                    modal.querySelector('[name="neighborhood"]').value = btn.dataset.neighborhood;
                    modal.querySelector('[name="geo_lat"]').value = btn.dataset.geo_lat;
                    modal.querySelector('[name="geo_lng"]').value = btn.dataset.geo_lng;
                    modal.querySelector('[name="amenities"]').value = btn.dataset.amenities;
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-property');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deletePropertyModal');
                    modal.querySelector('form').action = '/properties/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.title;
                });
            });
        });
    </script>
@endsection
