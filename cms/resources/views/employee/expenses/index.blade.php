@extends('employee.layouts.app')

@section('title', 'Expense Management')
@section('page-title', 'My Expenses')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <h4>Expense Management</h4>
            <p class="text-muted">Submit and track your office expenses</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employee.expenses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Submit New Expense
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Pending</h6>
                            <h3 class="text-warning">{{ $expenses->where('status', 'pending')->count() }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Approved</h6>
                            <h3 class="text-success">{{ $expenses->where('status', 'approved')->count() }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Rejected</h6>
                            <h3 class="text-danger">{{ $expenses->where('status', 'rejected')->count() }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Amount</h6>
                            <h3 class="text-primary">₹{{ number_format($expenses->where('status', 'approved')->sum('amount'), 2) }}</h3>
                        </div>
                        <i class="fas fa-rupee-sign fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">My Expense History</h5>
        </div>
        <div class="card-body">
            @if($expenses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                    <td>{{ Str::limit($expense->description, 50) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $expense->category }}</span>
                                    </td>
                                    <td>₹{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->payment_method }}</td>
                                    <td>
                                        @if($expense->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($expense->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('employee.expenses.show', $expense) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No expenses submitted yet</h5>
                    <p class="text-muted">Click "Submit New Expense" to get started</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection