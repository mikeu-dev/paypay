<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Check In
     * 
     * Record a check-in for the current day.
     * 
     * @group Attendance
     * @authenticated
     */
    public function checkIn(Request $request)
    {
        $employee = $request->user()->employee;
        if (!$employee) {
            return response()->json(['message' => 'Employee record not found'], 404);
        }

        // Simple Check-in logic: Create attendance record for today if not exists
        $today = Carbon::today()->toDateString();
        
        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => $today
            ],
            [
                'status' => 'present',
                'check_in' => Carbon::now()->toTimeString(),
            ]
        );

        if ($attendance->wasRecentlyCreated) {
             return response()->json(['message' => 'Checked in successfully', 'data' => $attendance]);
        }

        return response()->json(['message' => 'Already checked in for today', 'data' => $attendance]);
    }

    /**
     * Check Out
     * 
     * Record a check-out for the current day.
     * 
     * @group Attendance
     * @authenticated
     */
    public function checkOut(Request $request)
    {
        $employee = $request->user()->employee;
        if (!$employee) {
            return response()->json(['message' => 'Employee record not found'], 404);
        }

        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();

        if (!$attendance) {
            return response()->json(['message' => 'No check-in record found for today'], 404);
        }

        $attendance->update([
            'check_out' => Carbon::now()->toTimeString(),
        ]);

        return response()->json(['message' => 'Checked out successfully', 'data' => $attendance]);
    }
}
