@extends('auth.layouts.app')
<style>
    .card-header{
        margin-top: 70px;
        display: flex;
    }
    .btn-secondary{
        margin-left: 600px;
    }
</style>
@section('title', 'Intern Callbacks')

@section('content')
<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Intern Callbacks</h4>
            <a href="{{ route('admin.interns.index') }}" class="btn btn-secondary">Back to Interns</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Internship Type</th>
                            <th>Callback Date</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>WhatsApp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($callbacks as $callback)
                        <tr>
                            <td>{{ $callback->name }}</td>
                            <td>{{ $callback->number }}</td>
                            <td>{{ $callback->role }}</td>
                            <td>{{ $callback->callback_date->format('d M Y') }}</td>
                            <td>{{ Str::limit($callback->notes, 30) }}</td>
                            <td>
                                <select class="status-select" data-id="{{ $callback->id }}">
                                    <option value="">Select Status</option>
                                    <option value="interested">Interested</option>
                                    <option value="not_interested">Not Interested</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="wrong_number">Wrong Number</option>
                                </select>
                            </td>
                            <td>
                                @php
                                $message = "Follow up for internship opportunity.\n\n".
                                "Position: {$callback->role} Intern\n".
                                "Company: Kwikster Innovative Optimisations Pvt Ltd.\n\n".
                                "Are you still interested?\n\n".
                                "Best Regards,\nHR Team";
                                @endphp
                                <a href="https://wa.me/91{{ $callback->number }}?text={{ urlencode($message) }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editCallback({{ $callback->id }}, '{{ $callback->callback_date }}', '{{ $callback->notes }}')">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteCallback({{ $callback->id }})">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No callbacks found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($callbacks->hasPages())
                {{ $callbacks->links() }}
            @endif
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Update Callback Status</h5>
            <span class="close">&times;</span>
        </div>
        <form id="statusForm">
            <div class="modal-body">
                <div class="form-group">
                    <label>New Status</label>
                    <select name="status" required>
                        <option value="">Select Status</option>
                        <option value="interested">Interested</option>
                        <option value="not_interested">Not Interested</option>
                        <option value="rejected">Rejected</option>
                        <option value="wrong_number">Wrong Number</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Reason/Notes</label>
                    <textarea name="reason" rows="3" placeholder="Enter reason or notes..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update Status</button>
                <button type="button" class="btn btn-secondary" onclick="closeStatusModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Callback Modal -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Edit Callback</h5>
            <span class="close">&times;</span>
        </div>
        <form id="editForm">
            <div class="modal-body">
                <div class="form-group">
                    <label>Callback Date</label>
                    <input type="date" name="callback_date" required>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #fefefe;
    padding: 0;
    border: 1px solid #888;
    width: 500px;
    border-radius: 8px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.modal-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.modal-footer {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding: 20px;
    border-top: 1px solid #eee;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.status-select {
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    font-size: 12px;
    width: 140px;
}
</style>

<script>
let currentCallbackId = null;

// Status change handler
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const callbackId = this.dataset.id;
            const status = this.value;
            const row = this.closest('tr');
            
            if (!status) return;
            
            // Show reason modal for certain statuses
            if (['not_interested', 'rejected'].includes(status)) {
                showReasonModal(callbackId, status, row);
            } else {
                updateCallbackStatus(callbackId, status, '', row);
            }
        });
    });
});

function showReasonModal(callbackId, status, row) {
    const reason = prompt(`Please provide reason for ${status.replace('_', ' ')}:`);
    if (reason !== null) {
        updateCallbackStatus(callbackId, status, reason, row);
    } else {
        // Reset dropdown if cancelled
        row.querySelector('.status-select').value = '';
    }
}

function updateCallbackStatus(callbackId, status, reason, row) {
    const formData = new FormData();
    formData.append('status', status);
    formData.append('reason', reason);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    fetch(`/admin/interns/callbacks/${callbackId}/status`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Status updated successfully!');
            // Redirect to appropriate page
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                // Remove row from table if no redirect
                row.remove();
            }
        } else {
            alert('Error updating status');
            row.querySelector('.status-select').value = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error occurred');
        row.querySelector('.status-select').value = '';
    });
}

function editCallback(id, date, notes) {
    currentCallbackId = id;
    document.querySelector('#editForm input[name="callback_date"]').value = date;
    document.querySelector('#editForm textarea[name="notes"]').value = notes;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    currentCallbackId = null;
}

function deleteCallback(id) {
    if (confirm('Are you sure you want to delete this callback?')) {
        fetch(`/admin/interns/callbacks/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Edit form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/admin/interns/callbacks/${currentCallbackId}`, {
        method: 'PUT',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});
</script>
@endsection