<!-- Create Dispute Modal -->
<div class="modal fade" id="createDisputeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('disputes.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Create Dispute</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="mb-3">
          <label class="form-label">Lease</label>
          <select name="lease_id" class="form-control" required>
            @foreach($leases as $lease)
              <option value="{{ $lease->id }}">Lease #{{ $lease->id }} ({{ $lease->unit->unit_label ?? '-' }})</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Raised By</label>
          <select name="raised_by" class="form-control" required>
            @foreach($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Issue</label>
          <textarea name="issue" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-control" required>
            <option value="open">Open</option>
            <option value="in_review">In Review</option>
            <option value="resolved">Resolved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Evidence (JSON)</label>
          <textarea name="evidence" class="form-control" placeholder='["photo1.jpg", "doc.pdf"]'></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Admin Resolution Notes</label>
          <textarea name="admin_resolution_notes" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" type="submit">Create Dispute</button>
      </div>
    </form>
  </div>
</div>