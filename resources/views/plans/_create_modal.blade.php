<!-- Create Plan Modal -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('plans.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Create Plan') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Name') }}</label>
            <input name="name" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Trial Days') }}</label>
            <input name="trial_days" type="number" class="form-control">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Description') }}</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Monthly Price') }}</label>
            <input name="monthly_price" type="number" step="0.01" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Yearly Price') }}</label>
            <input name="yearly_price" type="number" step="0.01" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('Max Properties') }}</label>
            <input name="max_properties" type="number" class="form-control" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('Max Units') }}</label>
            <input name="max_units" type="number" class="form-control" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('Max Users') }}</label>
            <input name="max_users" type="number" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Features (one per line)') }}</label>
          <textarea name="features" class="form-control" rows="4" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"></textarea>
        </div>

        <div class="mb-3">
          <div class="form-check">
            <input name="is_active" class="form-check-input" type="checkbox" value="1" id="is_active" checked>
            <label class="form-check-label" for="is_active">
              {{ __('Active') }}
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Create Plan') }}</button>
      </div>
    </form>
  </div>
</div>