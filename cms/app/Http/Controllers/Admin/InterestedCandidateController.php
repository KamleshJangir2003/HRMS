<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterestedCandidate;
use Illuminate\Http\Request;

class InterestedCandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = InterestedCandidate::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('number', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }
        
        $candidates = $query->orderBy('interested_at', 'desc')->paginate(15);
        return view('auth.admin.interested-candidates.index', compact('candidates'));
    }

    public function updateStatus(Request $request, $id)
    {
        $candidate = InterestedCandidate::findOrFail($id);
        $candidate->status = $request->status;
        $candidate->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function addNotes(Request $request, $id)
    {
        $candidate = InterestedCandidate::findOrFail($id);
        $candidate->notes = $request->notes;
        $candidate->save();
        
        return response()->json(['success' => true, 'message' => 'Notes added successfully']);
    }
}