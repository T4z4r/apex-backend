<!-- Create Lease Modal -->
<div class="modal fade" id="createLeaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('leases.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Create Lease') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="mb-3">
          <label class="form-label">{{ __('Unit') }}</label>
          <select name="unit_id" class="form-control" required>
            @foreach($units as $unit)
              <option value="{{ $unit->id }}">{{ $unit->unit_label }} ({{ $unit->property->title ?? '-' }})</option>
            @endforeach
          </select>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Tenant') }}</label>
            <select name="tenant_id" class="form-control" required>
              @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Landlord') }}</label>
            <select name="landlord_id" class="form-control" required>
              @foreach($landlords as $landlord)
                <option value="{{ $landlord->id }}">{{ $landlord->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Start Date') }}</label>
            <input name="start_date" type="date" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('End Date') }}</label>
            <input name="end_date" type="date" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Rent Amount') }}</label>
            <input name="rent_amount" type="number" step="0.01" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Deposit Amount') }}</label>
            <input name="deposit_amount" type="number" step="0.01" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Payment Frequency') }}</label>
            <select name="payment_frequency" class="form-control" required>
              <option value="monthly">{{ __('Monthly') }}</option>
              <option value="weekly">{{ __('Weekly') }}</option>
              <option value="quarterly">{{ __('Quarterly') }}</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-control" required>
              <option value="pending">{{ __('Pending') }}</option>
              <option value="active">{{ __('Active') }}</option>
              <option value="expired">{{ __('Expired') }}</option>
              <option value="terminated">{{ __('Terminated') }}</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">{{ __('Lease PDF URL') }}</label>
          <input name="lease_pdf_url" type="url" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Create Lease') }}</button>
      </div>
    </form>
  </div>
</div>
