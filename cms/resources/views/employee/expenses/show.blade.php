@extends('employee.layouts.app')

@section('title', 'Expense Details')
@section('page-title', 'Expense Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Expense Details</h5>
                    <span class="badge 
                        @if($expense->status === 'pending') bg-warning
                        @elseif($expense->status === 'approved') bg-success
                        @else bg-danger @endif fs-6">
                        {{ ucfirst($expense->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Expense Date</h6>
                            <p>{{ $expense->expense_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Amount</h6>
                            <p class="h4 text-primary">₹{{ number_format($expense->amount, 2) }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Category</h6>
                            <p><span class="badge bg-secondary">{{ $expense->category }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment Method</h6>
                            <p>{{ $expense->payment_method }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Description</h6>
                        <p>{{ $expense->description }}</p>
                    </div>

                    @if($expense->receipt_path)
                        <div class="mb-3">
                            <h6 class="text-muted">Receipt</h6>
                            <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-file-alt me-2"></i>View Receipt
                            </a>
                        </div>
                    @endif

                    @if($expense->admin_notes)
                        <div class="mb-3">
                            <h6 class="text-muted">Admin Notes</h6>
                            <div class="alert alert-info">
                                {{ $expense->admin_notes }}
                            </div>
                        </div>
                    @endif

                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <strong>Submitted:</strong> {{ $expense->created_at->format('d M Y, h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong> {{ $expense->updated_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('employee.expenses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Expenses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection