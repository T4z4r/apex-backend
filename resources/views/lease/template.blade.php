<!DOCTYPE html>
<html>
<head>
    <title>Lease Agreement</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { text-align: center; }
        .section { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Lease Agreement</h1>

    <div class="section">
        <strong>Tenant:</strong> {{ $lease->tenant->name }}<br>
        <strong>Landlord:</strong> {{ $lease->landlord->name }}<br>
        <strong>Property:</strong> {{ $lease->unit->property->title }} - {{ $lease->unit->unit_label }}<br>
        <strong>Address:</strong> {{ $lease->unit->property->address }}<br>
    </div>

    <div class="section">
        <strong>Lease Period:</strong> {{ $lease->start_date }} to {{ $lease->end_date }}<br>
        <strong>Rent Amount:</strong> {{ number_format($lease->rent_amount) }}<br>
        <strong>Deposit:</strong> {{ number_format($lease->deposit_amount) }}<br>
        <strong>Payment Frequency:</strong> {{ $lease->payment_frequency }}<br>
    </div>

    <div class="section">
        <strong>Tenant Signature:</strong> {{ $lease->tenant->name }}<br>
        <strong>Date Signed:</strong> {{ $lease->signed_at ?? 'Pending' }}<br>
    </div>

    <div class="section">
        <p>By signing this document, both parties agree to the terms of this lease.</p>
    </div>
</body>
</html>
