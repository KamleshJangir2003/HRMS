<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalaryCalculatorController extends Controller
{
    public function index()
    {
        return view('salary.calculator');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'in_hand_salary' => 'required|numeric|min:1'
        ]);

        $inHand = $request->in_hand_salary;
        
        // Calculate gross by reverse engineering from in-hand
        $gross = $this->calculateGrossFromInHand($inHand);
        
        // Basic = 60% of Gross
        $basic = $gross * 0.60;
        
        // HRA = 40% of Gross  
        $hra = $gross * 0.40;
        
        // PF calculations with cap rule
        $pfBasic = ($basic >= 15000) ? 15000 : $basic;
        $employeePf = $pfBasic * 0.12;
        $employerPf = $pfBasic * 0.13;
        
        // ESIC calculations (only if Gross <= 21000)
        if ($gross <= 21000) {
            $employeeEsic = $gross * 0.0075;
            $employerEsic = $gross * 0.0325;
        } else {
            $employeeEsic = 0;
            $employerEsic = 0;
        }
        
        // CTC = Gross + Employer contributions
        $ctc = $gross + $employerPf + $employerEsic;

        return view('salary.calculator', [
            'gross' => $gross,
            'basic' => $basic,
            'hra' => $hra,
            'employee_pf' => $employeePf,
            'employer_pf' => $employerPf,
            'employee_esic' => $employeeEsic,
            'employer_esic' => $employerEsic,
            'in_hand' => $inHand,
            'ctc' => $ctc
        ]);
    }
    
    private function calculateGrossFromInHand($inHand)
    {
        // Iterative approach to find gross that results in desired in-hand
        $gross = $inHand;
        
        for ($i = 0; $i < 10; $i++) {
            $basic = $gross * 0.60;
            $pfBasic = ($basic >= 15000) ? 15000 : $basic;
            $employeePf = $pfBasic * 0.12;
            
            $employeeEsic = ($gross <= 21000) ? $gross * 0.0075 : 0;
            
            $calculatedInHand = $gross - $employeePf - $employeeEsic;
            
            if (abs($calculatedInHand - $inHand) < 0.01) {
                break;
            }
            
            $gross = $gross + ($inHand - $calculatedInHand);
        }
        
        return round($gross, 2);
    }
}
