@extends('employee.layouts.app')

@section('title', 'My Tickets')
@section('page-title', 'Support Tickets')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Create New Ticket -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Create New Ticket</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('employee.tickets.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Brief description of issue">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-select" required>
                            <option value="office">Office Complaint</option>
                            <option value="personal">Personal Issue</option>
                            <option value="technical">Technical Problem</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="4" required placeholder="Detailed description of your complaint/issue"></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit Ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- My Tickets -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>My Tickets</h5>
            <span class="badge bg-info">{{ $tickets->count() }} Total</span>
        </div>
        <div class="card-body">
            @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ $ticket->title }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($ticket->type) }}</span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($ticket->priority == 'urgent') bg-danger
                                        @elseif($ticket->priority == 'high') bg-warning
                                        @elseif($ticket->priority == 'medium') bg-info
                                        @else bg-success
                                        @endif">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($ticket->status == 'open') bg-primary
                                        @elseif($ticket->status == 'in_progress') bg-warning
                                        @elseif($ticket->status == 'resolved') bg-success
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                    @if($ticket->viewed_at && $ticket->status == 'open')
                                        <small class="text-success d-block">
                                            <i class="fas fa-eye"></i> Viewed by admin
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ticketModal{{ $ticket->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No tickets found</h5>
                    <p class="text-muted">Create your first support ticket above</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Ticket Detail Modals -->
@foreach($tickets as $ticket)
<div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ticket #{{ $ticket->id }} - {{ $ticket->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Type:</strong> {{ ucfirst($ticket->type) }}
                    </div>
                    <div class="col-md-6">
                        <strong>Priority:</strong> 
                        <span class="badge 
                            @if($ticket->priority == 'urgent') bg-danger
                            @elseif($ticket->priority == 'high') bg-warning
                            @elseif($ticket->priority == 'medium') bg-info
                            @else bg-success
                            @endif">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong> 
                        <span class="badge 
                            @if($ticket->status == 'open') bg-primary
                            @elseif($ticket->status == 'in_progress') bg-warning
                            @elseif($ticket->status == 'resolved') bg-success
                            @else bg-secondary
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Created:</strong> {{ $ticket->created_at->format('M d, Y g:i A') }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Description:</strong>
                    <div class="border p-3 mt-2 bg-light">
                        {{ $ticket->description }}
                    </div>
                </div>

                @if($ticket->admin_response)
                <div class="mb-3">
                    <strong>Admin Response:</strong>
                    <div class="border p-3 mt-2 bg-success bg-opacity-10 border-success">
                        <i class="fas fa-reply me-2 text-success"></i>
                        {{ $ticket->admin_response }}
                        @if($ticket->resolved_at)
                            <small class="text-muted d-block mt-2">
                                Resolved on {{ $ticket->resolved_at->format('M d, Y g:i A') }}
                            </small>
                        @endif
                    </div>
                </div>
                @elseif($ticket->viewed_at)
                <div class="alert alert-info">
                    <i class="fas fa-eye me-2"></i>
                    Your complaint has been viewed by admin. You will receive a response soon.
                    <small class="d-block mt-1">Viewed on {{ $ticket->viewed_at->format('M d, Y g:i A') }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection