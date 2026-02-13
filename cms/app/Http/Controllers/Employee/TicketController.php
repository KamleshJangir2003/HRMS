<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('employee_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('employee.tickets', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:office,personal,technical,other',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        Ticket::create([
            'employee_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority
        ]);

        return redirect()->back()->with('success', 'Ticket submitted successfully! Admin will review it soon.');
    }
}