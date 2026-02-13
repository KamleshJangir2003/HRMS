<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        try {
            $results = [];
            
            // Employees
            $employees = Employee::where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orWhere('contact_number', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();
                
            foreach($employees as $emp) {
                $url = ($emp->hired_status == 'hired') ? '/admin/employees/hired' : '/admin/employees/' . $emp->id . '/details';
                
                $results[] = [
                    'id' => $emp->id,
                    'name' => trim($emp->first_name . ' ' . $emp->last_name),
                    'number' => $emp->phone ?: $emp->contact_number,
                    'type' => ($emp->hired_status == 'hired') ? 'Hired Employee' : 'Employee',
                    'page' => 'Employees',
                    'url' => $url
                ];
            }
            
            // Leads
            $leads = DB::table('leads')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('number', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();
                
            foreach($leads as $lead) {
                $url = match($lead->condition_status) {
                    'Rejected' => '/admin/leads/rejected',
                    'Interested' => '/admin/leads/interested',
                    'Not Interested' => '/admin/leads/not-interested', 
                    'Wrong Number' => '/admin/leads/wrong-number',
                    'Callback' => '/admin/callbacks',
                    default => '/admin/leads'
                };
                
                $results[] = [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'number' => $lead->number,
                    'type' => 'Lead (' . $lead->condition_status . ')',
                    'page' => 'Leads',
                    'url' => $url
                ];
            }
            
            // Callbacks
            $callbacks = DB::table('callbacks')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('number', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get();
                
            foreach($callbacks as $callback) {
                $results[] = [
                    'id' => $callback->id,
                    'name' => $callback->name,
                    'number' => $callback->number,
                    'type' => 'Callback',
                    'page' => 'Callbacks',
                    'url' => '/admin/callbacks'
                ];
            }
            
            // Interviews
            $interviews = DB::table('interviews')
                ->where('candidate_name', 'LIKE', "%{$query}%")
                ->orWhere('candidate_email', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get();
                
            foreach($interviews as $interview) {
                $url = match($interview->result) {
                    'Selected' => '/admin/interviews/selected',
                    default => '/admin/interviews/' . $interview->id
                };
                
                $results[] = [
                    'id' => $interview->id,
                    'name' => $interview->candidate_name,
                    'number' => $interview->candidate_email,
                    'type' => 'Interview (' . $interview->result . ')',
                    'page' => 'Interviews',
                    'url' => $url
                ];
            }
            
            return response()->json($results);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}