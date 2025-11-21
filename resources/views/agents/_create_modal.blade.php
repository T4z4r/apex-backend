<!-- Create Agent Modal -->
<div class="modal fade" id="createAgentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('agents.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Create Agent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="mb-3">
          <label class="form-label">User</label>
          <select name="user_id" class="form-control" required>
            @foreach($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Agency Name</label>
          <input name="agency_name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Commission Rate (%)</label>
          <input name="commission_rate" type="number" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Documents (JSON)</label>
          <textarea name="docs" class="form-control" placeholder='["license.pdf", "cert.pdf"]'></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" type="submit">Create Agent</button>
      </div>
    </form>
  </div>
</div>