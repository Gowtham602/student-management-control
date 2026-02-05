<?php

// app/Http/Controllers/Admin/AttendanceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Section;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
 public function index(Request $request)
{
    $currentYear = date('Y');
    $date = $request->date ?: date('Y-m-d');

    $students = Student::with('department','section')

        //  Only ACTIVE students
        ->where('passout_year', '>=', $currentYear)

        //  Search
        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('name','like',"%{$request->search}%")
                    ->orWhere('rollnum','like',"%{$request->search}%");
            });
        })

        //  Filters
        ->when($request->department, fn($q) =>
            $q->where('department_id', $request->department)
        )

        ->when($request->section, fn($q) =>
            $q->where('section_id', $request->section)
        )

        // 🎓 Optional year filter (1–4)
        ->when($request->year, function ($q) use ($request, $currentYear) {
            $q->whereRaw(
                "(? - admission_year + 1) = ?",
                [$currentYear, $request->year]
            );
        })

        //  ORDER BY YEAR LEVEL (1 → 4)
        ->orderByRaw(
            "(? - admission_year + 1) ASC",
            [$currentYear]
        )

        //  Order inside each year
        ->orderBy('rollnum')

        ->get();

    return view('admin.attendance.index', [
        'students'    => $students,
        'date'        => $date,
        'departments' => Department::orderBy('name')->get(),
        'sections'    => Section::orderBy('name')->get(),
    ]);
}



    public function bulkSave(Request $request)
    {
        $request->validate([
            'date'     => 'required|date_format:Y-m-d', //  FIXED
            'status'   => 'required|in:P,A,H',
            'students' => 'required|array',
        ]);

        foreach ($request->students as $studentId) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date'       => $request->date, //  NO CARBON
                ],
                [
                    'status'     => $request->status,
                ]
            );
        }

        return back()->with('success','Attendance saved successfully');
    }
}
