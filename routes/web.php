<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payroll/{payroll}/payslip', function (\App\Models\Payroll $payroll) {
    if (!auth()->check()) {
        abort(403);
    }
    return view('payroll.payslip', compact('payroll'));
})->name('payroll.payslip');

Route::get('/invoices/{invoice}/print', [\App\Http\Controllers\InvoiceController::class, 'print'])
    ->name('invoices.print')
    ->middleware('auth');
