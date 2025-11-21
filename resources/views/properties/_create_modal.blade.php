<!-- Create Property Modal -->
<div class="modal fade" id="createPropertyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('properties.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Create Property') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="mb-3">
          <label class="form-label">{{ __('Title') }}</label>
          <input name="title" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Description') }}</label>
          <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Address') }}</label>
          <input name="address" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Neighborhood') }}</label>
          <input name="neighborhood" class="form-control" required>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Geo Latitude') }}</label>
            <input name="geo_lat" type="number" step="0.0000001" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Geo Longitude') }}</label>
            <input name="geo_lng" type="number" step="0.0000001" class="form-control">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Amenities (JSON)') }}</label>
          <textarea name="amenities" class="form-control" placeholder='["pool", "gym"]'></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Create Property') }}</button>
      </div>
    </form>
  </div>
</div>
