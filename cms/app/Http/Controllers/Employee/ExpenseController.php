<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('employee_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('employee.expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'category' => 'required|in:Birthday,Office Supplies,Travel,Food,Others',
            'payment_method' => 'required|in:PhonePe,UPI,Scanner,Others',
            'expense_date' => 'required|date|before_or_equal:today',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create([
            'employee_id' => Auth::id(),
            'amount' => $request->amount,
            'description' => $request->description,
            'category' => $request->category,
            'payment_method' => $request->payment_method,
            'expense_date' => $request->expense_date,
            'receipt_path' => $receiptPath,
            'status' => 'pending'
        ]);

        return redirect()->route('employee.expenses.index')
            ->with('success', 'Expense submitted successfully!');
    }

    public function show(Expense $expense)
    {
        if ($expense->employee_id !== Auth::id()) {
            abort(403);
        }

        return view('employee.expenses.show', compact('expense'));
    }
}