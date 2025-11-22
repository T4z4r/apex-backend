<!-- Create Subscription Modal -->
<div class="modal fade" id="createSubscriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('subscriptions.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Create Subscription') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Tenant') }}</label>
            <select name="tenant_id" class="form-select" required>
              <option value="">{{ __('Select Tenant') }}</option>
              @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Plan') }}</label>
            <select name="plan_id" class="form-select" required>
              <option value="">{{ __('Select Plan') }}</option>
              @foreach($plans as $plan)
                <option value="{{ $plan->id }}">{{ $plan->name }} (${{ number_format($plan->monthly_price, 2) }}/month)</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Billing Cycle') }}</label>
            <select name="billing_cycle" class="form-select" required>
              <option value="monthly">{{ __('Monthly') }}</option>
              <option value="yearly">{{ __('Yearly') }}</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-select" required>
              <option value="active">{{ __('Active') }}</option>
              <option value="trial">{{ __('Trial') }}</option>
              <option value="expired">{{ __('Expired') }}</option>
              <option value="cancelled">{{ __('Cancelled') }}</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Trial Ends At') }}</label>
            <input name="trial_ends_at" type="date" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('Ends At') }}</label>
            <input name="ends_at" type="date" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Create Subscription') }}</button>
      </div>
    </form>
  </div>
</div>