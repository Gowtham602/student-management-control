<?php

// app/Http/Controllers/Admin/AttendanceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $students = Student::orderBy('rollnum')->get();

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $attendances = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn ($a) => $a->student_id . '_' . $a->date);

        return view('admin.attendance.index', compact(
            'students',
            'daysInMonth',
            'month',
            'year',
            'attendances'
        ));
    }

    public function save(Request $request)
    {
        Attendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'date' => $request->date,
            ],
            [
                'status' => $request->status,
            ]
        );

        return response()->json(['success' => true]);
    }
}
