<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Student, Attendance, Department, Section};
use Illuminate\Http\Request;
use App\Services\SmsService;
use Illuminate\Support\Facades\DB;

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

    $students = collect();
    $attendanceExists = false;

    // Load students only if filters selected
    if ($request->filled(['department','section','year'])) {

        $students = Student::with([
                'department',
                'section',
                'attendances' => function ($q) use ($date) {
                    $q->whereDate('date', $date);
                }
            ])
            ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])
            ->where('passout_year', '>=', $currentYear)
            ->where('department_id', $request->department)
            ->where('section_id', $request->section)
            ->whereRaw("(? - admission_year + 1) = ?", [
                $currentYear,
                (int)$request->year
            ])
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', "%{$request->search}%")
                        ->orWhere('rollnum', 'like', "%{$request->search}%");
                });
            })
            ->orderBy('rollnum')
            ->get();

        // Check if attendance already marked
      if ($students->isNotEmpty()) {

    $attendanceCount = Attendance::whereDate('date', $date)
        ->whereIn('student_id', $students->pluck('id'))
        ->count();

    $attendanceExists = $attendanceCount === $students->count();
}

    }

    return view('admin.attendance.index', [
        'students'         => $students,
        'date'             => $date,
        'departments'      => Department::orderBy('name')->get(),
        'sections'         => Section::orderBy('name')->get(),
        'attendanceExists' => $attendanceExists,
    ]);
}


    // section for drop in search 
    public function sections($departmentId)
    {
        return Section::where('department_id', $departmentId)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    // auto search for all 
//    public function ajaxStudents(Request $request)
// {
//     $currentYear = now()->year;
//     $date = $request->date ?? now()->toDateString();

//     $students = collect();

//     if ($request->hasAny(['department','section','year'])) {

//         $students = Student::with([
//             'department',
//             'section',
//             'attendances' => function ($q) use ($date) {
//                 $q->whereDate('date', $date);
//             }
//         ])
//         ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])
//         ->where('passout_year', '>=', $currentYear)

//         ->when($request->search, function ($q) use ($request) {
//             $q->where(function ($s) use ($request) {
//                 $s->where('name', 'like', "%{$request->search}%")
//                   ->orWhere('rollnum', 'like', "%{$request->search}%");
//             });
//         })

//         ->where('department_id', $request->department)
//         ->where('section_id', $request->section)
//         ->whereRaw("(? - admission_year + 1) = ?", [
//             $currentYear,
//             (int)$request->year
//         ])

//         ->orderBy('rollnum')
//         ->get();
//     }

//     return view('admin.attendance.partials.students', compact('students'));
// }

public function ajaxStudents(Request $request)
{
    $currentYear = now()->year;
    $date = $request->date ?? now()->toDateString();

    $students = collect();
    $attendanceExists = false;

    if ($request->filled(['department','section','year'])) {

        $students = Student::with([
            'department',
            'section',
            'attendances' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            }
        ])
        ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])   // ADD THIS
        ->where('passout_year', '>=', $currentYear) 
        ->where('department_id', $request->department)
        ->where('section_id', $request->section)
        ->whereRaw("(? - admission_year + 1) = ?", [
            $currentYear,
            (int)$request->year
        ])
        ->orderBy('rollnum')
        ->get();

        if ($students->isNotEmpty()) {

            $attendanceCount = Attendance::whereDate('date', $date)
                ->whereIn('student_id', $students->pluck('id'))
                ->count();

            $attendanceExists = $attendanceCount === $students->count();
        }
    }

    return view('admin.attendance.partials.students', [
        'students' => $students,
        'attendanceExists' => $attendanceExists
    ]);
}



    
public function bulkSave(Request $request)
{
    $request->validate([
        'date'       => 'required|date|before_or_equal:today',
        'department' => 'required',
        'section'    => 'required',
        'year'       => 'required',
    ]);

    DB::beginTransaction();

    try {

        $currentYear = now()->year;
        $alreadyMarked = Attendance::where('date', $request->date)

            ->whereIn('student_id', function ($q) use ($request, $currentYear) {
                $q->select('id')
                ->from('students')
                ->where('department_id', $request->department)
                ->where('section_id', $request->section)
                ->whereRaw("(? - admission_year + 1) = ?", [
                    now()->year,
                    (int)$request->year
                ]);
            })
        ->exists();

        if ($alreadyMarked) {
            return back()->with('error', 'Attendance already marked for this date.');
        }


        $students = Student::where('department_id', $request->department)
            ->where('section_id', $request->section)
            ->whereRaw("(? - admission_year + 1) = ?", [
                $currentYear,
                (int)$request->year
            ])
            ->get();

        // Convert to integer array
        $absentIds = collect($request->students ?? [])
                        ->map(fn($id) => (int)$id)
                        ->toArray();

        foreach ($students as $student) {

            $status = in_array($student->id, $absentIds)
                ? 'A'
                : 'P';

            Attendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'date'       => $request->date
                ],
                [
                    'status' => $status
                ]
            );
        }

        

        DB::commit();

        return back()->with('success', 'Attendance saved successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}

 

public function update(Request $request)
{
    $attendance = Attendance::updateOrCreate(
        [
            'student_id' => $request->student_id,
            'date' => $request->date
        ],
        [
            'status' => $request->status
        ]
    );

    //  If Absent → Send OTP
    if ($request->status == 'A') {

        $otp = rand(100000, 999999);

        $attendance->update([
            'otp' => $otp
        ]);

      
    
    }

    return back()->with('success', 'Attendance Updated');
}



    /*
    |--------------------------------------------------------------------------
    | Day-wise Attendance
    |--------------------------------------------------------------------------
    */
    // public function dayList(Request $request)
    // {
    //     $date = $request->date ?? now()->toDateString();

    //     $students = Student::with([
    //         'attendances' => fn ($q) => $q->where('date', $date)
    //     ])
    //     ->orderBy('rollnum')
    //     ->get();

    //     return view('admin.attendance.day', compact('students', 'date'));
    // }
    // public function dayList(Request $request)
    // {
    //     $date = $request->date ?? now()->toDateString();
    //     $currentYear = now()->year;

    //     $students = Student::with(['attendances' => function ($q) use ($date) {
    //         $q->whereDate('date', $date);
    //     }])

    //         //  ONLY CURRENT STUDENTS (I–IV YEAR)
    //         ->whereRaw(
    //             "(? - admission_year + 1) BETWEEN 1 AND 4",
    //             [$currentYear]
    //         )

    //         //  YEAR FILTER (1 / 2 / 3 / 4)
    //         ->when($request->year, function ($q) use ($request, $currentYear) {
    //             $q->whereRaw(
    //                 "(? - admission_year + 1) = ?",
    //                 [$currentYear, (int) $request->year]
    //             );
    //         })

    //         ->orderByRaw("(? - admission_year + 1)", [$currentYear])
    //         ->orderBy('rollnum')
    //         ->get();

    //     return view('admin.attendance.day', compact('students', 'date'));
    // }


// public function dayList(Request $request)
// {
//     $currentYear = now()->year;
//     $date = $request->date ?? now()->toDateString();

//     // Start empty query
//     $query = Student::with([
//         'department',
//         'section',
//         'attendances' => function ($q) use ($date) {
//             $q->whereDate('date', $date);
//         }
//     ])
//     ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear]);

//     if ($request->filled(['department','section','year'])) {

//         $query->where('department_id', $request->department)
//               ->where('section_id', $request->section)
//               ->whereRaw("(? - admission_year + 1) = ?", [
//                   $currentYear,
//                   (int) $request->year
//               ]);
//     } else {
//         // Force empty result but keep paginator
//         $query->whereRaw("1 = 0");
//     }

//     $students = $query->orderBy('rollnum')
//                       ->paginate(15)
//                       ->withQueryString();

//     return view('admin.attendance.day', [
//         'students' => $students,
//         'date' => $date,
//         'departments' => Department::orderBy('name')->get(),
//         'sections' => $request->department
//             ? Section::where('department_id', $request->department)
//                 ->orderBy('name')
//                 ->get()
//             : collect(),
//     ]);
// }


public function dayList(Request $request)
{
    $currentYear = now()->year;
    $date = $request->date ?? now()->toDateString();

    $query = Student::with([
        'department',
        'section',
        'attendances' => function ($q) use ($date) {
            $q->whereDate('date', $date);
        }
    ])
    ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear]);

    if ($request->filled(['department','section','year'])) {

        $query->where('department_id', $request->department)
              ->where('section_id', $request->section)
              ->whereRaw("(? - admission_year + 1) = ?", [
                  $currentYear,
                  (int) $request->year
              ]);

    } else {
        $query->whereRaw("1 = 0");
    }

    // Get paginated students (for table)
    $students = (clone $query)
                    ->orderBy('rollnum')
                    ->paginate(15)
                    ->withQueryString();

    // Get all students (for counting only)
    $allStudents = (clone $query)->get();

    $presentCount = 0;
    $absentCount  = 0;
    $notMarked    = 0;

    foreach ($allStudents as $student) {
        $attendance = $student->attendances->first();

        if (!$attendance) {
            $notMarked++;
        } elseif ($attendance->status === 'P') {
            $presentCount++;
        } elseif ($attendance->status === 'A') {
            $absentCount++;
        }
    }

    return view('admin.attendance.day', [
        'students'     => $students,
        'date'         => $date,
        'presentCount' => $presentCount,
        'absentCount'  => $absentCount,
        'notMarked'    => $notMarked,
        'departments'  => Department::orderBy('name')->get(),
        'sections'     => $request->department
            ? Section::where('department_id', $request->department)
                ->orderBy('name')
                ->get()
            : collect(),
    ]);
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
            'attendances as present_days' => fn($q) =>
            $q->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'P'),

            'attendances as absent_days' => fn($q) =>
            $q->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'A'),

            'attendances as holiday_days' => fn($q) =>
            $q->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'H'),
        ])->get();

        $totalDays = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->distinct('date')
            ->count('date');

        return view('admin.attendance.summary', compact(
            'students',
            'month',
            'year',
            'totalDays'
        ));
    }
}
