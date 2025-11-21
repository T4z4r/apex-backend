<!-- Create Unit Modal -->
<div class="modal fade" id="createUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('units.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Create Unit') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="mb-3">
          <label class="form-label">{{ __('Property') }}</label>
          <select name="property_id" class="form-control" required>
            @foreach($properties as $p)
              <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3"><label class="form-label">{{ __('Unit Label') }}</label><input name="unit_label" class="form-control" required></div>

        <div class="row">
          <div class="col-md-4 mb-3"><label class="form-label">{{ __('Bedrooms') }}</label><input name="bedrooms" type="number" class="form-control"></div>
          <div class="col-md-4 mb-3"><label class="form-label">{{ __('Bathrooms') }}</label><input name="bathrooms" type="number" class="form-control"></div>
          <div class="col-md-4 mb-3"><label class="form-label">{{ __('Size (m²)') }}</label><input name="size_m2" step="0.01" type="number" class="form-control"></div>
        </div>

        <div class="mb-3"><label class="form-label">{{ __('Rent Amount') }}</label><input name="rent_amount" type="number" step="0.01" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">{{ __('Deposit Amount') }}</label><input name="deposit_amount" type="number" step="0.01" class="form-control"></div>

        <div class="form-check">
          <input name="is_available" class="form-check-input" type="checkbox" id="create_unit_available" checked>
          <label class="form-check-label" for="create_unit_available">{{ __('Is Available') }}</label>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Create Unit') }}</button>
      </div>
    </form>
  </div>
</div>

