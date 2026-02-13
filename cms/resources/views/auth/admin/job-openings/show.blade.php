@extends('auth.layouts.app')

@section('content')
<style>
.dashboard-wrapper {
    padding: 25px;
    margin-left: 130px;
    margin-top: 60px;
}
.detail-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.detail-row {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}
.detail-row:last-child {
    border-bottom: none;
}
.status-badge {
    font-size: 14px;
    padding: 8px 16px;
}
</style>

<div class="dashboard-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-eye me-2"></i>Job Opening Details</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.job-openings.edit', $jobOpening) }}" class="btn btn-warning">
                <i class="fa-solid fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.job-openings.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card detail-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h3 class="mb-0">{{ $jobOpening->job_title }}</h3>
                        <span class="badge status-badge {{ $jobOpening->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($jobOpening->status) }}
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Shift:</strong>
                            </div>
                            <div class="col-md-9">
                                <span class="badge bg-info">{{ $jobOpening->shift }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Salary:</strong>
                            </div>
                            <div class="col-md-9">
                                <span class="h5 text-success">₹{{ number_format($jobOpening->salary, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Job Timing:</strong>
                            </div>
                            <div class="col-md-9">
                                {{ $jobOpening->job_timing }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Estimated Time to Hire:</strong>
                            </div>
                            <div class="col-md-9">
                                {{ $jobOpening->estimated_time_to_hire }} days
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Created:</strong>
                            </div>
                            <div class="col-md-9">
                                {{ $jobOpening->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Last Updated:</strong>
                            </div>
                            <div class="col-md-9">
                                {{ $jobOpening->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Job Description:</strong>
                            </div>
                            <div class="col-md-9">
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($jobOpening->job_description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex gap-2">
                        @if($jobOpening->status === 'active')
                            <form action="{{ route('admin.job-openings.close', $jobOpening) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Mark this job as hired/closed?')">
                                    <i class="fa-solid fa-check me-2"></i>Mark as Hired/Closed
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.job-openings.activate', $jobOpening) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-info">
                                    <i class="fa-solid fa-redo me-2"></i>Reopen Job
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.job-openings.destroy', $jobOpening) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this job opening?')">
                                <i class="fa-solid fa-trash me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection