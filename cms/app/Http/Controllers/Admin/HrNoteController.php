<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HrNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrNoteController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $todayNotes = HrNote::whereDate('date', $today)
                           ->where('status', 'pending')
                           ->orderBy('created_at', 'desc')
                           ->get();
        $allNotes = HrNote::orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.hr-notes.index', compact('todayNotes', 'allNotes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date'
        ]);

        HrNote::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
            'date' => $request->date,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.hr-notes.index')->with('success', 'Task added successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);
        
        $note = HrNote::findOrFail($id);
        $note->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $request->status
        ]);
    }

    public function destroy($id)
    {
        $note = HrNote::findOrFail($id);
        $note->delete();

        return redirect()->route('admin.hr-notes.index')->with('success', 'Task deleted successfully!');
    }
}