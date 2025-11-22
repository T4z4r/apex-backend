@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Plans') }}</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPlanModal">{{ __('Add Plan') }}</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="plansTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Monthly Price') }}</th>
                    <th>{{ __('Yearly Price') }}</th>
                    <th>{{ __('Max Properties') }}</th>
                    <th>{{ __('Max Units') }}</th>
                    <th>{{ __('Max Users') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $plan->name }}</td>
                        <td>${{ number_format($plan->monthly_price, 2) }}</td>
                        <td>${{ number_format($plan->yearly_price, 2) }}</td>
                        <td>{{ $plan->max_properties }}</td>
                        <td>{{ $plan->max_units }}</td>
                        <td>{{ $plan->max_users }}</td>
                        <td>
                            @if($plan->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit-plan" data-id="{{ $plan->id }}"
                                data-name="{{ $plan->name }}" data-description="{{ $plan->description }}"
                                data-monthly_price="{{ $plan->monthly_price }}" data-yearly_price="{{ $plan->yearly_price }}"
                                data-max_properties="{{ $plan->max_properties }}" data-max_units="{{ $plan->max_units }}"
                                data-max_users="{{ $plan->max_users }}" data-features="{{ json_encode($plan->features) }}"
                                data-is_active="{{ $plan->is_active }}" data-trial_days="{{ $plan->trial_days }}"
                                data-bs-toggle="modal" data-bs-target="#editPlanModal">
                                {{ __('Edit') }}
                            </button>

                            <button class="btn btn-sm btn-danger btn-delete-plan" data-id="{{ $plan->id }}"
                                data-name="{{ $plan->name }}" data-bs-toggle="modal"
                                data-bs-target="#deletePlanModal">
                                {{ __('Delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('plans._create_modal')
    @include('plans._edit_modal')
    @include('plans._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#plansTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-plan');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editPlanModal');
                    modal.querySelector('form').action = '/plans/' + btn.dataset.id;
                    modal.querySelector('[name="name"]').value = btn.dataset.name;
                    modal.querySelector('[name="description"]').value = btn.dataset.description;
                    modal.querySelector('[name="monthly_price"]').value = btn.dataset.monthly_price;
                    modal.querySelector('[name="yearly_price"]').value = btn.dataset.yearly_price;
                    modal.querySelector('[name="max_properties"]').value = btn.dataset.max_properties;
                    modal.querySelector('[name="max_units"]').value = btn.dataset.max_units;
                    modal.querySelector('[name="max_users"]').value = btn.dataset.max_users;
                    modal.querySelector('[name="trial_days"]').value = btn.dataset.trial_days;
                    modal.querySelector('[name="is_active"]').checked = btn.dataset.is_active === '1';

                    // Handle features
                    const featuresTextarea = modal.querySelector('[name="features"]');
                    const features = JSON.parse(btn.dataset.features || '[]');
                    featuresTextarea.value = features.join('\n');
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-plan');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deletePlanModal');
                    modal.querySelector('form').action = '/plans/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.name;
                });
            });
        });
    </script>
@endsection