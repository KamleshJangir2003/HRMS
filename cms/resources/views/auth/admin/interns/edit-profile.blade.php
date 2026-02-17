@extends('auth.layouts.app')

@section('title', 'Edit Intern Profile')

@section('content')
<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Edit Profile - {{ $intern->name }}</h4>
            <a href="{{ route('admin.interns.ongoing-list') }}" class="btn btn-secondary">Back to Ongoing Interns</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.interns.update-profile', $intern->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Intern Name</label>
                            <input type="text" class="form-control" value="{{ $intern->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Course/Program *</label>
                            <select name="course" class="form-control" required>
                                <option value="">Select Course</option>
                                <option value="Web Development" {{ $intern->course == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                <option value="Mobile App Development" {{ $intern->course == 'Mobile App Development' ? 'selected' : '' }}>Mobile App Development</option>
                                <option value="Digital Marketing" {{ $intern->course == 'Digital Marketing' ? 'selected' : '' }}>Digital Marketing</option>
                                <option value="Data Science" {{ $intern->course == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                <option value="UI/UX Design" {{ $intern->course == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                                <option value="Python Programming" {{ $intern->course == 'Python Programming' ? 'selected' : '' }}>Python Programming</option>
                                <option value="Java Programming" {{ $intern->course == 'Java Programming' ? 'selected' : '' }}>Java Programming</option>
                                <option value="React Development" {{ $intern->course == 'React Development' ? 'selected' : '' }}>React Development</option>
                                <option value="Node.js Development" {{ $intern->course == 'Node.js Development' ? 'selected' : '' }}>Node.js Development</option>
                                <option value="Flutter Development" {{ $intern->course == 'Flutter Development' ? 'selected' : '' }}>Flutter Development</option>
                                <option value="SEO & Content Marketing" {{ $intern->course == 'SEO & Content Marketing' ? 'selected' : '' }}>SEO & Content Marketing</option>
                                <option value="Social Media Marketing" {{ $intern->course == 'Social Media Marketing' ? 'selected' : '' }}>Social Media Marketing</option>
                                <option value="Graphic Design" {{ $intern->course == 'Graphic Design' ? 'selected' : '' }}>Graphic Design</option>
                                <option value="Video Editing" {{ $intern->course == 'Video Editing' ? 'selected' : '' }}>Video Editing</option>
                                <option value="Other" {{ $intern->course == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Select HR for Commission *</label>
                            <select name="hr_id" class="form-control" required>
                                <option value="">Select HR</option>
                                @php
                                    $hrs = \App\Models\Employee::where('department', 'HR')->get();
                                @endphp
                                @foreach($hrs as $hr)
                                    <option value="{{ $hr->id }}" {{ $intern->hr_id == $hr->id ? 'selected' : '' }}>{{ $hr->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Select Mentor Teacher *</label>
                            <select name="mentor_id" class="form-control" required>
                                <option value="">Select Mentor</option>
                                @php
                                    $mentors = \App\Models\Employee::whereIn('department', ['Development', 'Design', 'Marketing', 'Training', 'Technical'])->get();
                                @endphp
                                @foreach($mentors as $mentor)
                                    <option value="{{ $mentor->id }}" {{ $intern->mentor_id == $mentor->id ? 'selected' : '' }}>{{ $mentor->full_name }} ({{ $mentor->department }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Start Date *</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $intern->start_date ? $intern->start_date->format('Y-m-d') : '' }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Duration (Months) *</label>
                            <select name="internship_duration" class="form-control" required>
                                <option value="">Select Duration</option>
                                <option value="1" {{ $intern->internship_duration == 1 ? 'selected' : '' }}>1 Month</option>
                                <option value="2" {{ $intern->internship_duration == 2 ? 'selected' : '' }}>2 Months</option>
                                <option value="3" {{ $intern->internship_duration == 3 ? 'selected' : '' }}>3 Months</option>
                                <option value="6" {{ $intern->internship_duration == 6 ? 'selected' : '' }}>6 Months</option>
                                <option value="12" {{ $intern->internship_duration == 12 ? 'selected' : '' }}>12 Months</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stipend Amount (₹)</label>
                            <input type="number" name="stipend" class="form-control" min="0" value="{{ $intern->stipend }}" placeholder="Enter stipend amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="text" class="form-control" value="{{ $intern->end_date ? $intern->end_date->format('d M Y') : 'Auto calculated' }}" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Profile Details</label>
                    <textarea name="profile_details" class="form-control" rows="3" placeholder="Add additional profile information, skills, background, etc.">{{ $intern->profile_details }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Notes/Comments</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Any additional notes or comments">{{ $intern->notes }}</textarea>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection