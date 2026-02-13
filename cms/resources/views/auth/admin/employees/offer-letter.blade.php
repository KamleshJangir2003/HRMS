<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offer Letter - {{ $employee->full_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 30px;
            color: #333;
            font-size: 14px;
        }
        .header {
            margin-bottom: 40px;
        }
        .company-info {
            font-size: 12px;
            line-height: 1.4;
        }
        .date {
            margin: 30px 0;
            font-weight: bold;
        }
        .candidate-name {
            margin-bottom: 30px;
            font-weight: bold;
        }
        .content {
            text-align: justify;
            margin-bottom: 20px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .ctc-highlight {
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
        }
        .terms-section {
            margin: 30px 0;
            page-break-inside: avoid;
        }
        .terms-title {
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        .terms-content {
            margin-bottom: 15px;
        }
        .signature-section {
            margin-top: 60px;
            text-align: center;
        }
        .signature-line {
            margin-top: 50px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            21/281, Kaveri path, Madhyam Marg Road,<br>
            Mansarovar, Jaipur, Rajasthan, 302020<br>
            +91 96805 80889<br>
            hr@thekwikster.com<br>
            www.thekwikster.com
        </div>
    </div>

    <div class="date">
        DATE: {{ date('jS \of F, Y') }}
    </div>

    <div class="candidate-name">
        Dear {{ $employee->full_name }},
    </div>

    <div class="content">
        <p>We are delighted to extend an offer of employment to you for the position of <strong>{{ $employee->department ?? 'Lead Generation Executive' }}</strong> at <strong>Kwikster Innovative Optimisations Pvt Ltd</strong> starting from <strong>{{ $employee->joining_date ? $employee->joining_date->format('jS \of F, Y') : date('jS \of F, Y') }}</strong>. After a rigorous selection process, your qualifications, skills, and interview performance have stood out, and we are excited to have you join our team.</p>

        @if($employee->current_ctc)
        <div class="ctc-highlight">
            Your Annual Total CTC will be ₹{{ number_format($employee->current_ctc, 0) }}.
        </div>
        @endif

        <p>Please note that you will be working 5.5 days per week and your base location will be Jaipur.</p>

        <p>Please indicate your acceptance of this offer by signing and returning this letter by <strong>{{ date('jS \of F, Y', strtotime('+7 days')) }}</strong> through email.</p>

        <p>If you have any questions or require further information, please feel free to contact HR team at humanresources@thekwikster.com</p>

        <p>We are excited to have you join our team and hope you will have a successful career with Kwikster.</p>
    </div>

    <div class="terms-section">
        <div class="terms-title">
            Please note the following terms and conditions:
        </div>
        
        <div class="terms-content">
            <p>The selected candidate will be required to undergo a five (5) days certification/training period. Attendance during this certification/training period is mandatory, and no leave will be permitted. Any absence or leave taken during these five (5) days may result in immediate termination of the offer.</p>
            
            <p>Employment shall commence with a Probation Period of three months, which may be extended at the sole discretion of the Company based on performance, discipline, and business requirements.</p>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-line">
            {{ $employee->full_name }}<br>
            CEO, Kwikster Innovative Optimisations Pvt Ltd
        </div>
    </div>
</body>
</html>