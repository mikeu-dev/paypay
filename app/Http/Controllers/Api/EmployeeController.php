<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Get Employee Detail
     * 
     * Get the employee profile associated with the authenticated user.
     * 
     * @group Employee
     * @authenticated
     */
    public function profile(Request $request)
    {
        return response()->json($request->user()->employee);
    }

    /**
     * Get Payslips
     * 
     * Get a list of payslips for the authenticated employee.
     * 
     * @group Employee
     * @authenticated
     */
    public function payslips(Request $request)
    {
        $employee = $request->user()->employee;
        if (!$employee) {
             return response()->json(['data' => []]);
        }
        
        // Return simple list, or pagination
        return response()->json($employee->payrolls()->orderBy('period_end', 'desc')->get());
    }
}
