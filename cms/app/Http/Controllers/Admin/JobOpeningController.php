<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use Illuminate\Http\Request;

class JobOpeningController extends Controller
{
    public function index()
    {
        $jobOpenings = JobOpening::orderBy('created_at', 'desc')->get();
        return view('auth.admin.job-openings.index', compact('jobOpenings'));
    }

    public function create()
    {
        $jobTitles = [
            'Software Developer',
            'Data Analyst',
            'Customer Support',
            'Sales Executive',
            'HR Executive',
            'Marketing Executive',
            'Accountant',
            'Project Manager',
            'Quality Assurance',
            'Business Analyst'
        ];
        
        return view('auth.admin.job-openings.create', compact('jobTitles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'shift' => 'required|in:Day,Night',
            'salary' => 'required|numeric|min:0',
            'job_timing' => 'required|string|max:255',
            'estimated_time_to_hire' => 'required|integer|min:1',
            'job_description' => 'required|string'
        ]);

        JobOpening::create($request->all());

        // Create notification
        \App\Helpers\NotificationHelper::jobOpening($request->job_title);

        return redirect()->route('admin.job-openings.index')
            ->with('success', 'Job opening created successfully!');
    }

    public function show(JobOpening $jobOpening)
    {
        return view('auth.admin.job-openings.show', compact('jobOpening'));
    }

    public function edit(JobOpening $jobOpening)
    {
        $jobTitles = [
            'Software Developer',
            'Data Analyst',
            'Customer Support',
            'Sales Executive',
            'HR Executive',
            'Marketing Executive',
            'Accountant',
            'Project Manager',
            'Quality Assurance',
            'Business Analyst'
        ];
        
        return view('auth.admin.job-openings.edit', compact('jobOpening', 'jobTitles'));
    }

    public function update(Request $request, JobOpening $jobOpening)
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'shift' => 'required|in:Day,Night',
            'salary' => 'required|numeric|min:0',
            'job_timing' => 'required|string|max:255',
            'estimated_time_to_hire' => 'required|integer|min:1',
            'job_description' => 'required|string'
        ]);

        $jobOpening->update($request->all());

        return redirect()->route('admin.job-openings.index')
            ->with('success', 'Job opening updated successfully!');
    }

    public function destroy(JobOpening $jobOpening)
    {
        $jobOpening->delete();
        return redirect()->route('admin.job-openings.index')
            ->with('success', 'Job opening deleted successfully!');
    }

    public function markClosed(JobOpening $jobOpening)
    {
        $jobOpening->update(['status' => 'closed']);
        return redirect()->route('admin.job-openings.index')
            ->with('success', 'Job opening marked as closed!');
    }

    public function markActive(JobOpening $jobOpening)
    {
        $jobOpening->update(['status' => 'active']);
        return redirect()->route('admin.job-openings.index')
            ->with('success', 'Job opening marked as active!');
    }

    public function getActiveJobs()
    {
        $activeJobs = JobOpening::active()->get(['id', 'job_title', 'shift', 'salary']);
        return response()->json(['jobs' => $activeJobs]);
    }
}