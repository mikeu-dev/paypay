<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->period_start->format('M Y') }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #f3f4f6;
            padding: 40px;
        }
        .payslip-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .payslip-title {
            font-size: 18px;
            margin-top: 10px;
        }
        .employee-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .employee-info td {
            padding: 5px;
        }
        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 5px;
        }
        .amount {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            border-top: 1px solid #333;
        }
        .net-salary {
            margin-top: 30px;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            border: 2px solid #333;
            padding: 10px;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .payslip-container {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>

<div class="payslip-container">
    <div class="header">
        <div class="company-name">PT. PAYPAY INDONESIA</div>
        <div class="payslip-title">PAYSLIP</div>
        <div>Period: {{ $payroll->period_start->format('d M Y') }} - {{ $payroll->period_end->format('d M Y') }}</div>
    </div>

    <table class="employee-info">
        <tr>
            <td width="150">Employee ID</td>
            <td>: {{ $payroll->employee->employee_code }}</td>
            <td width="150">Department</td>
            <td>: {{ $payroll->employee->department ?? '-' }}</td>
        </tr>
        <tr>
            <td>Name</td>
            <td>: {{ $payroll->employee->name }}</td>
            <td>Position</td>
            <td>: {{ $payroll->employee->position }}</td>
        </tr>
    </table>

    <div class="section-title">EARNINGS</div>
    <table class="details-table">
        <tr>
            <td>Basic Salary</td>
            <td class="amount">{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
        </tr>
        @foreach($payroll->allowanceDetails as $detail)
        <tr>
            <td>{{ $detail->name }}</td>
            <td class="amount">{{ number_format($detail->amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td>Total Earnings</td>
            <td class="amount">{{ number_format($payroll->basic_salary + $payroll->total_allowances, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="section-title">DEDUCTIONS</div>
    <table class="details-table">
        @foreach($payroll->deductionDetails as $detail)
        <tr>
            <td>{{ $detail->name }}</td>
            <td class="amount">{{ number_format($detail->amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td>Total Deductions</td>
            <td class="amount">{{ number_format($payroll->total_deductions, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="net-salary">
        NET SALARY: Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
    </div>
    
    <div style="margin-top: 50px; text-align: center; display: flex; justify-content: space-between; padding: 0 50px;">
        <div>
            <br><br><br><br>
            (____________________)
            <br>HR Manager
        </div>
        <div>
            <br><br><br><br>
            (____________________)
            <br>{{ $payroll->employee->name }}
        </div>
    </div>
</div>

<script>
    // window.print();
</script>

</body>
</html>
