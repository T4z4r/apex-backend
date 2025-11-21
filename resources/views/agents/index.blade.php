@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Agents</h3>
            @can('manage agents')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAgentModal">Add Agent</button>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="agentsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Agency Name</th>
                    <th>Commission Rate (%)</th>
                    <th>Verified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agents as $agent)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $agent->user->name ?? '-' }}</td>
                        <td>{{ $agent->agency_name }}</td>
                        <td>{{ $agent->commission_rate }}%</td>
                        <td>{{ $agent->verified_at ? 'Yes' : 'No' }}</td>
                        <td>
                            @can('manage agents')
                                <button class="btn btn-sm btn-warning btn-edit-agent" data-id="{{ $agent->id }}"
                                    data-user_id="{{ $agent->user_id }}" data-agency_name="{{ $agent->agency_name }}"
                                    data-commission_rate="{{ $agent->commission_rate }}" data-docs="{{ $agent->docs }}"
                                    data-bs-toggle="modal" data-bs-target="#editAgentModal">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger btn-delete-agent" data-id="{{ $agent->id }}"
                                    data-agency_name="{{ $agent->agency_name }}" data-bs-toggle="modal"
                                    data-bs-target="#deleteAgentModal">
                                    Delete
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('agents._create_modal')
    @include('agents._edit_modal')
    @include('agents._delete_modal')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if available
            if (window.jQuery && $.fn.DataTable) {
                $('#agentsTable').DataTable();
            }

            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-agent');
            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('editAgentModal');
                    modal.querySelector('form').action = '/agents/' + btn.dataset.id;
                    modal.querySelector('[name="user_id"]').value = btn.dataset.user_id;
                    modal.querySelector('[name="agency_name"]').value = btn.dataset.agency_name;
                    modal.querySelector('[name="commission_rate"]').value = btn.dataset.commission_rate;
                    modal.querySelector('[name="docs"]').value = btn.dataset.docs;
                });
            });

            // Delete modal population
            const delButtons = document.querySelectorAll('.btn-delete-agent');
            delButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('deleteAgentModal');
                    modal.querySelector('form').action = '/agents/' + btn.dataset.id;
                    modal.querySelector('.delete-item-name').textContent = btn.dataset.agency_name;
                });
            });
        });
    </script>
@endsection