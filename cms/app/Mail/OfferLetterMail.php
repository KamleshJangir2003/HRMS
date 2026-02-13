<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\EmployeeBankDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class OfferLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $bankDetail;

    public function __construct(Employee $employee, $bankDetail = null)
    {
        $this->employee = $employee;
        $this->bankDetail = $bankDetail;
    }

    public function build()
    {
        // Generate PDF
        $pdf = Pdf::loadView('auth.admin.employees.offer-letter', [
            'employee' => $this->employee,
            'bankDetail' => $this->bankDetail
        ]);
        
        $fileName = 'Offer_Letter_' . str_replace(' ', '_', $this->employee->full_name) . '_' . date('Y-m-d') . '.pdf';

        return $this->subject('Offer Letter - ' . $this->employee->full_name)
                    ->view('emails.offer-letter')
                    ->with([
                        'employee' => $this->employee,
                        'bankDetail' => $this->bankDetail
                    ])
                    ->attachData($pdf->output(), $fileName, [
                        'mime' => 'application/pdf',
                    ]);
    }
}