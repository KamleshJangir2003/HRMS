@extends('auth.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
.details-wrapper {
    padding: 25px;
    margin-left: 130px;
    margin-top: 60px;
}
.employee-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.profile-header {
    background: linear-gradient(135deg, #4f46e5, #3b82f6);
    color: white;
    border-radius: 16px 16px 0 0;
    padding: 30px;
}
.profile-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid white;
    object-fit: cover;
}
.detail-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}
.detail-row:last-child {
    border-bottom: none;
}
.detail-label {
    font-weight: 600;
    color: #495057;
    min-width: 150px;
}
.detail-value {
    color: #212529;
    flex: 1;
    text-align: right;
}
.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.btn-back {
    background: #6c757d;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    color: white;
}
</style>

<div class="details-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Employee Details</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card employee-card">
        <!-- Profile Header -->
        <div class="profile-header text-center">
            <img src="{{ $employee->profile_image ?? 'https://via.placeholder.com/120' }}" 
                 alt="Profile" class="profile-img mb-3">
            <h2 class="mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
            <p class="mb-0 opacity-75">{{ $employee->designation ?? 'Employee' }}</p>
        </div>

        <div class="card-body p-4">
            <!-- Personal Information -->
            <div class="detail-section">
                <h5 class="mb-3 text-primary">
                    <i class="bi bi-person-fill me-2"></i>Personal Information
                </h5>
                <div class="detail-row">
                    <span class="detail-label">Full Name:</span>
                    <span class="detail-value">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $employee->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $employee->phone ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date of Birth:</span>
                    <span class="detail-value">{{ $employee->date_of_birth ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">{{ $employee->address ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="detail-section">
                <h5 class="mb-3 text-success">
                    <i class="bi bi-briefcase-fill me-2"></i>Employment Details
                </h5>
                <div class="detail-row">
                    <span class="detail-label">Employee ID:</span>
                    <span class="detail-value">{{ $employee->employee_id ?? $employee->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Department:</span>
                    <span class="detail-value">{{ $employee->department ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Designation:</span>
                    <span class="detail-value">{{ $employee->designation ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Joining Date:</span>
                    <span class="detail-value">{{ $employee->joining_date ?? $employee->created_at->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Employment Type:</span>
                    <span class="detail-value">{{ $employee->employment_type ?? 'Full Time' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Salary:</span>
                    <span class="detail-value">{{ $employee->salary ? '₹' . number_format($employee->salary) : 'N/A' }}</span>
                </div>
            </div>

            <!-- Account Status -->
            <div class="detail-section">
                <h5 class="mb-3 text-warning">
                    <i class="bi bi-shield-check me-2"></i>Account Status
                </h5>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        @if($employee->is_approved)
                            <span class="status-badge bg-success">Active</span>
                        @else
                            <span class="status-badge bg-warning">Pending Approval</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">User Type:</span>
                    <span class="detail-value">{{ ucfirst($employee->user_type) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Last Login:</span>
                    <span class="detail-value">{{ $employee->last_login_at ?? 'Never' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account Created:</span>
                    <span class="detail-value">{{ $employee->created_at->format('d M Y, h:i A') }}</span>
                </div>
            </div>

            <!-- Documents -->
            @if($employee->documents && $employee->documents->count() > 0)
            <div class="detail-section">
                <h5 class="mb-3 text-info">
                    <i class="bi bi-file-earmark-text me-2"></i>Documents ({{ $employee->documents->count() }})
                </h5>
                @foreach($employee->documents as $document)
                <div class="detail-row">
                    <span class="detail-label">{{ ucwords(str_replace('_', ' ', $document->document_type)) }}:</span>
                    <span class="detail-value">
                        <span class="badge bg-{{ $document->status == 'verified' ? 'success' : ($document->status == 'submitted' ? 'info' : 'warning') }} me-2">
                            {{ ucfirst($document->status) }}
                        </span>
                        <a href="{{ route('employee.documents.view', $document->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <a href="{{ route('employee.documents.download', $document->id) }}" class="btn btn-sm btn-outline-success ms-1">
                            <i class="bi bi-download"></i> Download
                        </a>
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="detail-section">
                <h5 class="mb-3 text-info">
                    <i class="bi bi-file-earmark-text me-2"></i>Documents
                </h5>
                <div class="text-center py-3">
                    <i class="bi bi-file-earmark-x" style="font-size: 48px; color: #6c757d;"></i>
                    <p class="text-muted mt-2">No documents uploaded yet</p>
                </div>
            </div>
            @endif

            <!-- Additional Information -->
            <div class="detail-section">
                <h5 class="mb-3 text-secondary">
                    <i class="bi bi-info-circle me-2"></i>Additional Information
                </h5>
                <div class="detail-row">
                    <span class="detail-label">Emergency Contact:</span>
                    <span class="detail-value">{{ $employee->emergency_contact ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Blood Group:</span>
                    <span class="detail-value">{{ $employee->blood_group ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Skills:</span>
                    <span class="detail-value">{{ $employee->skills ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Experience:</span>
                    <span class="detail-value">{{ $employee->experience ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                @if(!$employee->is_approved)
                <form action="{{ route('admin.approve', $employee->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success me-2">
                        <i class="bi bi-check-circle"></i> Approve Employee
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.users') }}" class="btn btn-primary">
                    <i class="bi bi-people"></i> View All Employees
                </a>
            </div>
        </div>
    </div>
</div>
@endsection