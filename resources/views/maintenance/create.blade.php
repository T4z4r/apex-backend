@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Submit Maintenance Request</h3>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('maintenance.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="unit_id" class="form-label">Unit</label>
                        <select name="unit_id" id="unit_id" class="form-control" required>
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_label }} ({{ $unit->property->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" id="priority" class="form-control" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="photos" class="form-label">Photos (optional)</label>
                        <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/*">
                        <small class="form-text text-muted">You can upload multiple photos.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
