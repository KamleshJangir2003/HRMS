<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('employee')->orderBy('created_at', 'desc')->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function markViewed($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['viewed_at' => now()]);
        
        return redirect()->back()->with('success', 'Ticket marked as viewed');
    }

    public function respond(Request $request, $id)
    {
        $request->validate([
            'admin_response' => 'required|string',
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $ticket = Ticket::findOrFail($id);
        
        $updateData = [
            'admin_response' => $request->admin_response,
            'status' => $request->status,
            'viewed_at' => $ticket->viewed_at ?? now()
        ];

        if ($request->status == 'resolved') {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);
        
        return redirect()->back()->with('success', 'Response sent successfully');
    }
}