<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeBankDetail;
use App\Models\EmployeeDocument;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class EmployeeDocumentController extends Controller
{
    const REQUIRED_DOCUMENTS = [
        'aadhar_card',
        'pan_card',
        'marksheet_10th',
        'marksheet_12th',
        'graduation',
        'diploma',
        'post_graduation',
        'passbook',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /* =====================================================
       EMPLOYEE DOCUMENTS INDEX (own documents)
    ===================================================== */
    public function index()
    {
        $user = Auth::user();
        $documents = EmployeeDocument::where('user_id', $user->id)->get();
        $bankDetail = EmployeeBankDetail::where('user_id', $user->id)->first();

        $totalRequired  = count(self::REQUIRED_DOCUMENTS);
        $uploadedCount  = $documents->unique('document_type')->count();
        $verifiedCount  = $documents->where('status', 'verified')->unique('document_type')->count();
        $submittedCount = $documents->where('status', 'submitted')->unique('document_type')->count();
        $pendingCount   = $documents->whereIn('status', ['pending', 'uploaded'])->unique('document_type')->count();
        $isAdminView    = false;

        return view('auth.admin.employees.em_document', compact(
            'user',
            'documents',
            'bankDetail',
            'totalRequired',
            'uploadedCount',
            'verifiedCount',
            'submittedCount',
            'pendingCount',
            'isAdminView'
        ));
    }

    /* =====================================================
       ADMIN DOCUMENTS INDEX – LIST EMPLOYEES
    ===================================================== */
    public function adminDocumentsIndex()
    {
        $employees = Employee::where('user_type', 'employee')
            ->where(function($query) {
                $query->where('hired_status', '!=', 'hired')
                      ->orWhereNull('hired_status');
            })
            ->with(['documents' => function($query) {
                $query->select('user_id', 'document_type', 'status');
            }])
            ->orderBy('first_name')
            ->get()
            ->map(function($employee) {
                $documents = $employee->documents;
                $totalRequired = count(self::REQUIRED_DOCUMENTS);
                $uploadedCount = $documents->unique('document_type')->count();
                $verifiedCount = $documents->where('status', 'verified')->unique('document_type')->count();
                $submittedCount = $documents->where('status', 'submitted')->unique('document_type')->count();
                $pendingCount = $documents->whereIn('status', ['pending', 'uploaded'])->unique('document_type')->count();
                
                $employee->document_stats = [
                    'total_required' => $totalRequired,
                    'uploaded' => $uploadedCount,
                    'verified' => $verifiedCount,
                    'submitted' => $submittedCount,
                    'pending' => $pendingCount,
                    'missing' => $totalRequired - $uploadedCount,
                    'status' => $uploadedCount == 0 ? 'not_started' : 
                               ($verifiedCount == $totalRequired ? 'completed' : 
                               ($submittedCount > 0 ? 'submitted' : 
                               ($pendingCount > 0 ? 'pending' : 'in_progress')))
                ];
                
                return $employee;
            });

        return view('auth.admin.employees.documents_index', compact('employees'));
    }

    /* =====================================================
       ADMIN VIEW – EMPLOYEE DOCUMENTS ✅ FIXED
    ===================================================== */
    public function adminView($userId)
    {
        $user = Employee::findOrFail($userId);

        $documents = EmployeeDocument::where('user_id', $userId)->get();
        $bankDetail = EmployeeBankDetail::where('user_id', $userId)->first();

        $totalRequired  = count(self::REQUIRED_DOCUMENTS);
        $uploadedCount = $documents->unique('document_type')->count();
        $verifiedCount = $documents->where('status', 'verified')->unique('document_type')->count();
        $submittedCount = $documents->where('status', 'submitted')->unique('document_type')->count();
        $pendingCount  = $documents->whereIn('status', ['pending', 'uploaded'])->unique('document_type')->count();

        $isAdminView = true;

        return view('auth.admin.employees.em_document', compact(
            'user',
            'documents',
            'bankDetail',
            'totalRequired',
            'uploadedCount',
            'verifiedCount',
            'submittedCount',
            'pendingCount',
            'isAdminView'
        ));
    }

    /* =====================================================
       ADMIN UPLOAD DOCUMENT (for employee)
    ===================================================== */
    public function adminUploadDocument(Request $request, $userId)
    {
        $request->validate([
            'document_type' => 'required|in:' . implode(',', self::REQUIRED_DOCUMENTS),
            'document'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $employee = Employee::findOrFail($userId);

        if ($request->document_type !== 'salary_slips') {
            $existing = EmployeeDocument::where('user_id', $userId)
                ->where('document_type', $request->document_type)
                ->first();

            if ($existing && $existing->status === 'verified') {
                return back()->with('error', 'Verified document cannot be replaced');
            }

            if ($existing) {
                Storage::disk('public')->delete($existing->file_path);
                $existing->delete();
            }
        }

        $file = $request->file('document');
        $path = "documents/{$userId}/" . time() . '_' . $file->getClientOriginalName();

        Storage::disk('public')->put($path, file_get_contents($file));

        EmployeeDocument::create([
            'user_id'        => $userId,
            'document_type'  => $request->document_type,
            'document_name'  => $this->getDocumentDisplayName($request->document_type),
            'file_path'      => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size'      => $file->getSize(),
            'status'         => 'uploaded',
            'uploaded_at'    => now(),
        ]);

        return redirect()->route('admin.employees.document', ['userId' => $userId])
            ->with('success', 'Document uploaded successfully');
    }

    /* =====================================================
       ADMIN SAVE BANK DETAILS (for employee)
    ===================================================== */
    public function adminSaveBankDetails(Request $request, $userId)
    {
        $request->validate([
            'bank_name'       => 'required',
            'account_number'  => 'required',
            'ifsc_code'       => 'required',
            'account_type'    => 'required|in:savings,current',
        ]);

        EmployeeBankDetail::updateOrCreate(
            ['user_id' => $userId],
            $request->only('bank_name', 'account_number', 'ifsc_code', 'account_type')
        );

        return redirect()->route('admin.employees.document', ['userId' => $userId])
            ->with('success', 'Bank details saved successfully');
    }

    /* =====================================================
       ADMIN SUBMIT FOR VERIFICATION
    ===================================================== */
    public function adminSubmitForVerification($userId)
    {
        $uploadedTypes = EmployeeDocument::where('user_id', $userId)
            ->pluck('document_type')
            ->unique()
            ->toArray();

        $missing = array_diff(self::REQUIRED_DOCUMENTS, $uploadedTypes);

        if (!empty($missing)) {
            return back()->with('error', 'Missing documents: ' . implode(', ', array_map(fn($m) => ucwords(str_replace('_', ' ', $m)), $missing)));
        }

        // Update all uploaded documents to submitted status
        EmployeeDocument::where('user_id', $userId)
            ->where('status', 'uploaded')
            ->update(['status' => 'submitted']);

        // Send offer letter via email
        return $this->sendOfferLetterEmail($userId);
    }

    /* =====================================================
       SEND OFFER LETTER EMAIL
    ===================================================== */
    public function sendOfferLetterEmail($userId)
    {
        $employee = Employee::findOrFail($userId);
        $bankDetail = EmployeeBankDetail::where('user_id', $userId)->first();
        
        // Check if all required documents are submitted for verification
        $submittedTypes = EmployeeDocument::where('user_id', $userId)
            ->where('status', 'submitted')
            ->pluck('document_type')
            ->unique()
            ->toArray();

        $missing = array_diff(self::REQUIRED_DOCUMENTS, $submittedTypes);

        if (!empty($missing)) {
            return back()->with('error', 'Cannot send offer letter. Please submit all documents for verification first.');
        }

        try {
            \Mail::to($employee->email)->send(new \App\Mail\OfferLetterMail($employee, $bankDetail));
            
            return redirect()->route('admin.employees.document', ['userId' => $userId])
                ->with('success', 'Offer letter sent successfully to ' . $employee->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /* =====================================================
       EMPLOYEE UPLOAD DOCUMENT
    ===================================================== */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:' . implode(',', self::REQUIRED_DOCUMENTS),
            'document'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();

        if ($request->document_type !== 'salary_slips') {
            $existing = EmployeeDocument::where('user_id', $user->id)
                ->where('document_type', $request->document_type)
                ->first();

            if ($existing && $existing->status === 'verified') {
                return back()->with('error', 'Verified document cannot be replaced');
            }

            if ($existing) {
                Storage::disk('public')->delete($existing->file_path);
                $existing->delete();
            }
        }

        $file = $request->file('document');
        $path = "documents/{$user->id}/" . time() . '_' . $file->getClientOriginalName();

        Storage::disk('public')->put($path, file_get_contents($file));

        EmployeeDocument::create([
            'user_id'        => $user->id,
            'document_type'  => $request->document_type,
            'document_name'  => $this->getDocumentDisplayName($request->document_type),
            'file_path'      => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size'      => $file->getSize(),
            'status'         => 'uploaded',
            'uploaded_at'    => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully');
    }

    /* =====================================================
       SAVE BANK DETAILS
    ===================================================== */
    public function saveBankDetails(Request $request)
    {
        $request->validate([
            'bank_name'       => 'required',
            'account_number' => 'required',
            'ifsc_code'      => 'required',
            'account_type'   => 'required|in:savings,current',
        ]);

        EmployeeBankDetail::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('bank_name', 'account_number', 'ifsc_code', 'account_type')
        );

        return back()->with('success', 'Bank details saved successfully');
    }

    /* =====================================================
       VIEW DOCUMENT (EMPLOYEE + ADMIN) ✅ FIXED
    ===================================================== */
    public function viewDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        if (Auth::user()->user_type !== 'admin' && $doc->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->file(
            Storage::disk('public')->path($doc->file_path)
        );
    }

    /* =====================================================
       DOWNLOAD DOCUMENT (EMPLOYEE + ADMIN) ✅ FIXED
    ===================================================== */
    public function downloadDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        if (Auth::user()->user_type !== 'admin' && $doc->user_id !== Auth::id()) {
            abort(403);
        }

        return Storage::disk('public')->download(
            $doc->file_path,
            $doc->document_name . '.' . $doc->file_extension
        );
    }

    /* =====================================================
       DELETE DOCUMENT
    ===================================================== */
    public function deleteDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        if ($doc->status === 'verified') {
            return back()->with('error', 'Verified document cannot be deleted');
        }

        if (Auth::user()->user_type !== 'admin' && $doc->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return back()->with('success', 'Document deleted successfully');
    }

    /* =====================================================
       SUBMIT FOR VERIFICATION
    ===================================================== */
    public function submitForVerification()
    {
        $user = Auth::user();

        $uploadedTypes = EmployeeDocument::where('user_id', $user->id)
            ->pluck('document_type')
            ->unique()
            ->toArray();

        $missing = array_diff(self::REQUIRED_DOCUMENTS, $uploadedTypes);

        if (!empty($missing)) {
            return back()->with('error', 'Missing documents: ' . implode(', ', $missing));
        }

        // Update all uploaded documents to submitted status
        EmployeeDocument::where('user_id', $user->id)
            ->where('status', 'uploaded')
            ->update(['status' => 'submitted']);

        return back()->with('success', 'Documents submitted for verification');
    }

    private function getDocumentDisplayName($type)
    {
        return ucwords(str_replace('_', ' ', $type));
    }

    /* =====================================================
       HIRED EMPLOYEES INDEX
    ===================================================== */
    public function hiredEmployeesIndex()
    {
        $hiredEmployees = Employee::where('user_type', 'employee')
            ->where('hired_status', 'hired')
            ->where(function($query) {
                $query->where('employee_status', '!=', 'active')
                      ->orWhereNull('employee_status');
            })
            ->where(function($query) {
                $query->where('action_status', '!=', 'not_selected')
                      ->orWhereNull('action_status');
            })
            ->orderBy('joining_date', 'desc')
            ->get();

        return view('auth.admin.employees.hired_index', compact('hiredEmployees'));
    }

    /* =====================================================
       UPDATE HIRED EMPLOYEE DATA
    ===================================================== */
    public function updateHiredEmployee(Request $request, $userId)
    {
        $request->validate([
            'induction_round' => 'required|in:yes,no',
            'training' => 'required|in:yes,no',
            'certification_period' => 'required|integer|min:1|max:30',
            'action_status' => 'required|in:selected,not_selected,reason'
        ]);

        $employee = Employee::findOrFail($userId);
        $employee->update([
            'induction_round' => $request->induction_round,
            'training' => $request->training,
            'certification_period' => $request->certification_period,
            'action_status' => $request->action_status,
            'action_reason' => $request->action_reason ?? null
        ]);

        // If selected, move to next step (make them active employee)
        if ($request->action_status === 'selected') {
            $employee->update(['employee_status' => 'active']);
        }

        return back()->with('success', 'Employee data updated successfully');
    }

    /* =====================================================
       NOT SELECTED EMPLOYEES INDEX
    ===================================================== */
    public function notSelectedEmployeesIndex()
    {
        $notSelectedEmployees = Employee::where('user_type', 'employee')
            ->where('action_status', 'not_selected')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('auth.admin.employees.not_selected_index', compact('notSelectedEmployees'));
    }

    /* =====================================================
       UPDATE HIRED STATUS
    ===================================================== */
    public function updateHiredStatus(Request $request, $id)
    {
        $request->validate([
            'hired_status' => 'required|in:not_hired,hired'
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update([
            'hired_status' => $request->hired_status
        ]);

        return back()->with('success', 'Hired status updated successfully');
    }
}
