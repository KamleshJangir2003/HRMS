<!DOCTYPE html>
<html>
<head>
    <title>Intern Payslip - {{ $intern->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; color: #2eacb3; }
        .payslip-title { font-size: 18px; margin: 10px 0; }
        .intern-info, .payment-info { margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; margin: 5px 0; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f5f5f5; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Print Payslip</button>
        <a href="{{ route('admin.interns.payment', $intern->id) }}" class="btn btn-secondary">Back to Payment</a>
    </div>

    <div class="header">
        <div class="company-name">KWIKSTER</div>
        <div class="payslip-title">INTERN TRAINING RECEIPT</div>
        <div>Generated on: {{ date('d M Y') }}</div>
    </div>

    <div class="intern-info">
        <h3>Intern Information</h3>
        <div class="info-row">
            <span><strong>Name:</strong> {{ $intern->name }}</span>
            <span><strong>Intern ID:</strong> #{{ str_pad($intern->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <span><strong>Course:</strong> {{ $intern->course ?? 'Not Set' }}</span>
            <span><strong>Phone:</strong> {{ $intern->number ?? 'Not Set' }}</span>
        </div>
        <div class="info-row">
            <span><strong>Mentor:</strong> {{ $intern->mentor->full_name ?? 'Not Assigned' }}</span>
            <span><strong>HR:</strong> {{ $intern->hr->full_name ?? 'Not Assigned' }}</span>
        </div>
        <div class="info-row">
            <span><strong>Start Date:</strong> {{ $intern->start_date ? $intern->start_date->format('d M Y') : 'Not Set' }}</span>
            <span><strong>Duration:</strong> {{ $intern->internship_duration ?? 'Not Set' }} months</span>
        </div>
    </div>

    <div class="payment-info">
        <h3>Payment Summary</h3>
        <div class="info-row">
            <span><strong>Training Amount:</strong> ₹{{ number_format($intern->stipend ?? 0) }}</span>
            <span><strong>Total Paid:</strong> ₹{{ number_format($intern->total_paid ?? 0) }}</span>
        </div>
        <div class="info-row">
            <span><strong>Pending Amount:</strong> ₹{{ number_format(($intern->stipend ?? 0) - ($intern->total_paid ?? 0)) }}</span>
            <span></span>
        </div>
    </div>

    <h3>Payment History</h3>
    <table class="table">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Payment Date</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($intern->payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                <td>₹{{ number_format($payment->amount) }}</td>
                <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                <td>{{ $payment->notes ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No payments recorded</td>
            </tr>
            @endforelse
        </tbody>
        @if($intern->payments->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="2"><strong>Total Paid</strong></td>
                <td><strong>₹{{ number_format($intern->payments->sum('amount')) }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>This is a computer-generated payslip. No signature required.</p>
        <p>© {{ date('Y') }} Kwikster. All rights reserved.</p>
    </div>
</body>
</html>