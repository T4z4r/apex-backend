<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('users.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Create User') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="mb-3">
          <label class="form-label">{{ __('Name') }}</label>
          <input name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Email') }}</label>
          <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Phone') }}</label>
          <input name="phone" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Password') }}</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Confirm Password') }}</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Role') }}</label>
          <select name="role" class="form-control" required>
            <option value="">{{ __('Select Role') }}</option>
            @foreach($roles ?? [] as $role)
              <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Create User') }}</button>
      </div>
    </form>
  </div>
</div>