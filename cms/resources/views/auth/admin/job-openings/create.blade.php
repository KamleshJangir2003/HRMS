@extends('auth.layouts.app')

@section('content')
<style>
.dashboard-wrapper {
    padding: 25px;
    margin-left: 130px;
    margin-top: 60px;
}
.form-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>

<div class="dashboard-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-plus me-2"></i>Create Job Opening</h2>
        <a href="{{ route('admin.job-openings.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card">
                <div class="card-body p-4">
                    <form action="{{ route('admin.job-openings.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                <select class="form-select @error('job_title') is-invalid @enderror" 
                                        id="job_title" name="job_title" required>
                                    <option value="">Select Job Title</option>
                                    @foreach($jobTitles as $title)
                                        <option value="{{ $title }}" {{ old('job_title') === $title ? 'selected' : '' }}>
                                            {{ $title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="shift" class="form-label">Shift <span class="text-danger">*</span></label>
                                <select class="form-select @error('shift') is-invalid @enderror" 
                                        id="shift" name="shift" required>
                                    <option value="">Select Shift</option>
                                    <option value="Day" {{ old('shift') === 'Day' ? 'selected' : '' }}>Day</option>
                                    <option value="Night" {{ old('shift') === 'Night' ? 'selected' : '' }}>Night</option>
                                </select>
                                @error('shift')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">Salary (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('salary') is-invalid @enderror" 
                                       id="salary" name="salary" value="{{ old('salary') }}" required>
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="job_timing" class="form-label">Job Timing <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('job_timing') is-invalid @enderror" 
                                       id="job_timing" name="job_timing" value="{{ old('job_timing') }}" 
                                       placeholder="e.g., 9:00 AM - 6:00 PM" required>
                                @error('job_timing')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estimated_time_to_hire" class="form-label">Estimated Time to Hire (Days) <span class="text-danger">*</span></label>
                            <input type="number" min="1" 
                                   class="form-control @error('estimated_time_to_hire') is-invalid @enderror" 
                                   id="estimated_time_to_hire" name="estimated_time_to_hire" 
                                   value="{{ old('estimated_time_to_hire') }}" required>
                            @error('estimated_time_to_hire')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="job_description" class="form-label">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('job_description') is-invalid @enderror" 
                                      id="job_description" name="job_description" rows="5" required>{{ old('job_description') }}</textarea>
                            @error('job_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-2"></i>Create Job Opening
                            </button>
                            <a href="{{ route('admin.job-openings.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection