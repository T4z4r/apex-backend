@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Edit Maintenance Request</h3>

        <div class="card">
            <div class="card-body">
                <h5>{{ $maintenance->title }}</h5>
                <p><strong>Description:</strong> {{ $maintenance->description }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($maintenance->priority) }}</p>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}</p>
                <p><strong>Tenant:</strong> {{ $maintenance->tenant->name }}</p>
                <p><strong>Unit:</strong> {{ $maintenance->unit->unit_label }} ({{ $maintenance->unit->property->name }})</p>
                @if($maintenance->photos)
                    <p><strong>Photos:</strong></p>
                    @foreach(json_decode($maintenance->photos) as $photo)
                        <img src="{{ $photo }}" alt="Photo" class="img-thumbnail" style="max-width: 100px; margin: 5px;">
                    @endforeach
                @endif

                <form method="POST" action="{{ route('maintenance.update', $maintenance) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="open" {{ $maintenance->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $maintenance->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $maintenance->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="rejected" {{ $maintenance->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assigned To</label>
                        <select name="assigned_to" id="assigned_to" class="form-control">
                            <option value="">None</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $maintenance->assigned_to == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="resolution_notes" class="form-label">Resolution Notes</label>
                        <textarea name="resolution_notes" id="resolution_notes" class="form-control" rows="4">{{ $maintenance->resolution_notes }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
