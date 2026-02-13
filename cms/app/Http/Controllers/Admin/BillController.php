<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::orderBy('due_date', 'asc')->get();
        return view('admin.bills.index', compact('bills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date'
        ]);

        Bill::create([
            'bill_type' => $request->bill_type,
            'amount' => $request->amount,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'status' => 'pending'
        ]);

        return redirect()->route('admin.bills.index')->with('success', 'Bill added successfully!');
    }

    public function markAsPaid($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->update(['status' => 'paid']);

        return response()->json(['success' => true, 'message' => 'Bill marked as paid']);
    }

    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->delete();

        return response()->json(['success' => true, 'message' => 'Bill deleted successfully']);
    }

    public function getDueToday()
    {
        $bills = Bill::dueToday()->get();
        return response()->json(['bills' => $bills]);
    }
}