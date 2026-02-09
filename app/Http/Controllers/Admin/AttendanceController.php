<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Student, Attendance, Department, Section};
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Bulk Attendance Page
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $currentYear = now()->year;
        $date = $request->date ?? now()->toDateString();

        $students = Student::with('department','section')
            ->where('passout_year', '>=', $currentYear)

            ->when($request->search, fn ($q) =>
                $q->where('name','like',"%{$request->search}%")
                  ->orWhere('rollnum','like',"%{$request->search}%")
            )

            ->when($request->department, fn ($q) =>
                $q->where('department_id', $request->department)
            )

            ->when($request->section, fn ($q) =>
                $q->where('section_id', $request->section)
            )

            ->when($request->year, function ($q) use ($request, $currentYear) {
                $q->whereRaw("(? - admission_year + 1) = ?", [
                    $currentYear, $request->year
                ]);
            })

            ->orderByRaw("(? - admission_year + 1)", [$currentYear])
            ->orderBy('rollnum')
            ->get();

        return view('admin.attendance.index', [
            'students'    => $students,
            'date'        => $date,
            'departments' => Department::orderBy('name')->get(),
            'sections'    => Section::orderBy('name')->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Save Attendance
    |--------------------------------------------------------------------------
    */
    public function bulkSave(Request $request)
    {
        $request->validate([
            'date'     => 'required|date',
            'status'   => 'required|in:P,A,H',
            'students' => 'required|array',
        ]);

        foreach ($request->students as $studentId) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $request->date],
                ['status' => $request->status]
            );
        }

        return back()->with('success', 'Attendance saved successfully ');
    }

    /*
    |--------------------------------------------------------------------------
    | Day-wise Attendance
    |--------------------------------------------------------------------------
    */
    public function dayList(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $students = Student::with([
            'attendances' => fn ($q) => $q->where('date', $date)
        ])
        ->orderBy('rollnum')
        ->get();

        return view('admin.attendance.day', compact('students', 'date'));
    }

    /*
    |--------------------------------------------------------------------------
    | Month / Year Summary
    |--------------------------------------------------------------------------
    */
    public function summary(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $students = Student::withCount([
            'attendances as present_days' => fn ($q) =>
                $q->whereMonth('date', $month)
                  ->whereYear('date', $year)
                  ->where('status','P'),

            'attendances as absent_days' => fn ($q) =>
                $q->whereMonth('date', $month)
                  ->whereYear('date', $year)
                  ->where('status','A'),

            'attendances as holiday_days' => fn ($q) =>
                $q->whereMonth('date', $month)
                  ->whereYear('date', $year)
                  ->where('status','H'),
        ])->get();

        $totalDays = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->distinct('date')
            ->count('date');

        return view('admin.attendance.summary', compact(
            'students','month','year','totalDays'
        ));
    }
}
