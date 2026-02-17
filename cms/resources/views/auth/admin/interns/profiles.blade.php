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
@section('title', 'Intern Profiles')

@section('content')
<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Intern Profiles</h4>
            <a href="{{ route('admin.interns.index') }}" class="btn btn-secondary">Back to All Interns</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Mentor</th>
                            <th>HR</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interns as $intern)
                        <tr>
                            <td>{{ $intern->name }}</td>
                            <td>{{ $intern->course ?? 'Not Set' }}</td>
                            <td>{{ $intern->mentor->full_name ?? 'Not Assigned' }}</td>
                            <td>{{ $intern->hr->full_name ?? 'Not Assigned' }}</td>
                            <td><span class="badge bg-success">{{ $intern->final_result ?? 'Active' }}</span></td>
                            <td>
                                <a href="{{ route('admin.interns.profile', $intern->id) }}" class="btn btn-sm btn-primary">View Profile</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No intern profiles found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($interns->hasPages())
                {{ $interns->links() }}
            @endif
        </div>
    </div>
</div>
@endsection