@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Subscriptions') }}</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubscriptionModal">{{ __('Add Subscription') }}</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="subscriptionsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Tenant') }}</th>
                    <th>{{ __('Plan') }}</th>
                    <th>{{ __('Billing Cycle') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Trial Ends') }}</th>
                    <th>{{ __('Ends At') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscriptions as $subscription)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $subscription->tenant->name ?? 'N/A' }}</td>
                        <td>{{ $subscription->plan->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                        <td>${{ number_format($subscription->price, 2) }}</td>
                        <td>
                            @if($subscription->isActive())
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @elseif($subscription->isOnTrial())
                                <span class="badge bg-info">{{ __('Trial') }}</span>
                            @elseif($subscription->isExpired())
                                <span class="badge bg-danger">{{ __('Expired') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit-subscription" data-id="{{ $subscription->id }}"
                                data-tenant_id="{{ $subscription->tenant_id }}" data-plan_id="{{ $subscription->plan_id }}"
                                data-billing_cycle="{{ $subscription->billing_cycle }}" data-status="{{ $subscription->status }}"
                                data-trial_ends_at="{{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('Y-m-d') : '' }}"
                                data-ends_at="{{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : '' }}"
                                data-bs-toggle="modal" data-bs-target="#editSubscriptionModal">
                                {{ __('Edit') }}
                            </button>

                            <button class="btn btn-sm btn-danger btn-delete-subscription" data-id="{{ $subscription->id }}"
                                data-tenant="{{ $subscription->tenant->name ?? 'N/A' }}" data-bs-toggle="modal"
                                data-bs-target="#deleteSubscriptionModal">
                                {{ __('Delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('subscriptions._create_modal', ['plans' => $plans, 'tenants' => $tenants])
    @include('subscriptions._edit_modal', ['plans' => $plans, 'tenants' => $tenants])
    @include('subscriptions._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#subscriptionsTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-subscription');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editSubscriptionModal');
                    modal.querySelector('form').action = '/subscriptions/' + btn.dataset.id;
                    modal.querySelector('[name="tenant_id"]').value = btn.dataset.tenant_id;
                    modal.querySelector('[name="plan_id"]').value = btn.dataset.plan_id;
                    modal.querySelector('[name="billing_cycle"]').value = btn.dataset.billing_cycle;
                    modal.querySelector('[name="status"]').value = btn.dataset.status;
                    modal.querySelector('[name="trial_ends_at"]').value = btn.dataset.trial_ends_at;
                    modal.querySelector('[name="ends_at"]').value = btn.dataset.ends_at;
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-subscription');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deleteSubscriptionModal');
                    modal.querySelector('form').action = '/subscriptions/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.tenant;
                });
            });
        });
    </script>
@endsection