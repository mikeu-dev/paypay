<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;

class LeaveController extends Controller
{
    /**
     * List Leaves
     * 
     * Get a list of leave requests for the authenticated employee.
     * 
     * @group Leaves
     * @authenticated
     */
    public function index(Request $request)
    {
        $employee = $request->user()->employee;
        if (!$employee) {
            return response()->json([]);
        }

        return response()->json($employee->leaves()->orderBy('created_at', 'desc')->get());
    }

    /**
     * Request Leave
     * 
     * Submit a new leave request.
     * 
     * @group Leaves
     * @authenticated
     * @bodyParam type string required The type of leave (e.g., 'annual', 'sick'). Example: sick
     * @bodyParam start_date date required The start date. Example: 2025-12-10
     * @bodyParam end_date date required The end date. Example: 2025-12-11
     * @bodyParam reason string The reason for leave. Example: Flu
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $employee = $request->user()->employee;
        if (!$employee) {
            return response()->json(['message' => 'Employee record not found'], 404);
        }

        $leave = $employee->leaves()->create([
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return response()->json($leave, 201);
    }
}
