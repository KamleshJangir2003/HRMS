@extends('employee.layouts.app')

@section('title', 'Submit Expense')
@section('page-title', 'Submit New Expense')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Submit New Expense</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.expenses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expense_date" class="form-label">Expense Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('expense_date') is-invalid @enderror" 
                                           id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                                    @error('expense_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" placeholder="0.00">
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                        <option value="">Select Category</option>
                                        <option value="Birthday" {{ old('category') === 'Birthday' ? 'selected' : '' }}>Birthday</option>
                                        <option value="Office Supplies" {{ old('category') === 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                                        <option value="Travel" {{ old('category') === 'Travel' ? 'selected' : '' }}>Travel</option>
                                        <option value="Food" {{ old('category') === 'Food' ? 'selected' : '' }}>Food</option>
                                        <option value="Others" {{ old('category') === 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="PhonePe" {{ old('payment_method') === 'PhonePe' ? 'selected' : '' }}>PhonePe</option>
                                        <option value="UPI" {{ old('payment_method') === 'UPI' ? 'selected' : '' }}>UPI</option>
                                        <option value="Scanner" {{ old('payment_method') === 'Scanner' ? 'selected' : '' }}>Scanner</option>
                                        <option value="Others" {{ old('payment_method') === 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Describe the expense in detail...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="receipt" class="form-label">Receipt/Bill (Optional)</label>
                            <input type="file" class="form-control @error('receipt') is-invalid @enderror" 
                                   id="receipt" name="receipt" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">Upload receipt or bill (JPG, PNG, PDF - Max 2MB)</div>
                            @error('receipt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employee.expenses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Submit Expense
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection