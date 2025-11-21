<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('roles.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Create Role') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="mb-3">
          <label class="form-label">{{ __('Name') }}</label>
          <input name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Permissions') }}</label>
          <select name="permissions[]" class="form-select select2" multiple>
            @foreach($permissions as $permission)
              <option value="{{ $permission->id }}">{{ $permission->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Create Role') }}</button>
      </div>
    </form>
  </div>
</div>