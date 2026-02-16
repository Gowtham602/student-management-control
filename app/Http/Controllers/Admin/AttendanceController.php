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

        $students = collect(); // empty by default

if ($request->hasAny(['search','department','section','year'])) {

    $students = Student::with([
        'department',
        'section',
        'attendances' => function ($q) use ($date) {
            $q->whereDate('date', $date);
        }
    ])
    ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])
    ->where('passout_year', '>=', $currentYear)

    ->when($request->search, function ($q) use ($request) {
        $q->where(function ($sub) use ($request) {
            $sub->where('name', 'like', "%{$request->search}%")
                ->orWhere('rollnum', 'like', "%{$request->search}%");
        });
    })

    ->when($request->department,
        fn($q) => $q->where('department_id', $request->department)
    )

    ->when($request->section,
        fn($q) => $q->where('section_id', $request->section)
    )

    ->when($request->year, function ($q) use ($request, $currentYear) {
        $q->whereRaw(
            "(? - admission_year + 1) = ?",
            [$currentYear, (int)$request->year]
        );
    })

    ->orderBy('rollnum')
    ->get();
}



        // $students = Student::with('department', 'section')

        //     //  ONLY CURRENT STUDENTS (I–IV YEAR)
        //     ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])
        //     ->where('passout_year', '>=', $currentYear)

        //     //  SEARCH
        //     ->when($request->search, function ($q) use ($request) {
        //         $q->where(function ($sub) use ($request) {
        //             $sub->where('name', 'like', "%{$request->search}%")
        //                 ->orWhere('rollnum', 'like', "%{$request->search}%");
        //         });
        //     })

        //     //  DEPARTMENT
        //     ->when(
        //         $request->department,
        //         fn($q) =>
        //         $q->where('department_id', $request->department)
        //     )

        //     //  SECTION
        //     ->when(
        //         $request->section,
        //         fn($q) =>
        //         $q->where('section_id', $request->section)
        //     )

        //     //  YEAR FILTER (I / II / III / IV)
        //     ->when($request->year, function ($q) use ($request, $currentYear) {
        //         $q->whereRaw(
        //             "(? - admission_year + 1) = ?",
        //             [$currentYear, (int) $request->year]
        //         );
        //     })

        //     //  SORT BY YEAR → ROLL NO
        //     ->orderByRaw("(? - admission_year + 1)", [$currentYear])
        //     ->orderBy('rollnum')

        //     ->get();

        return view('admin.attendance.index', [
            'students'    => $students,
            'date'        => $date,
            'departments' => Department::orderBy('name')->get(),
            'sections'    => Section::orderBy('name')->get(),
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
   public function ajaxStudents(Request $request)
{
    $currentYear = now()->year;
    $date = $request->date ?? now()->toDateString();

    $students = collect();

    if ($request->hasAny(['department','section','year'])) {

        $students = Student::with([
            'department',
            'section',
            'attendances' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            }
        ])
        ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])
        ->where('passout_year', '>=', $currentYear)

        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($s) use ($request) {
                $s->where('name', 'like', "%{$request->search}%")
                  ->orWhere('rollnum', 'like', "%{$request->search}%");
            });
        })

        ->where('department_id', $request->department)
        ->where('section_id', $request->section)
        ->whereRaw("(? - admission_year + 1) = ?", [
            $currentYear,
            (int)$request->year
        ])

        ->orderBy('rollnum')
        ->get();
    }

    return view('admin.attendance.partials.students', compact('students'));
}




    /*
    |--------------------------------------------------------------------------
    | Bulk Save Attendance
    |--------------------------------------------------------------------------
    */
    // public function bulkSave(Request $request)
    // {
    //     $request->validate([
    //         'date'     => 'required|date',
    //         'status'   => 'required|in:P,A,H',
    //         'students' => 'required|array',
    //     ]);

    //     foreach ($request->students as $studentId) {
    //         Attendance::updateOrCreate(
    //             ['student_id' => $studentId, 'date' => $request->date],
    //             ['status' => $request->status]
    //         );
    //     }

    //     return back()->with('success', 'Attendance saved successfully ');
    // }

// current 
//   public function bulkSave(Request $request)
// {
//     $request->validate([
//         'date'     => 'required|date|before_or_equal:today',
//         'status'   => 'required|in:P,A,H',
//         'students' => 'required|array',
//     ]);

//     DB::beginTransaction();

//     try {

//         foreach ($request->students as $studentId) {

//             $student = Student::find($studentId);

//             Attendance::updateOrCreate(
//                 ['student_id' => $studentId, 'date' => $request->date],
//                 ['status' => $request->status]
//             );

//             /*
//             |--------------------------------------------------------------------------
//             | SEND OTP SMS ONLY IF ABSENT
//             |--------------------------------------------------------------------------
//             */

//             if (
//                 $request->status === 'A' &&
//                 $student &&
//                 !empty($student->father_phone)
//             ) {

//                 $otp = rand(100000, 999999);

//                 // MUST  change temp latter 
//                 $message = "Please use this OTP {$otp} for your registration. IDLSMS";

//                 SmsService::send($student->father_phone, $message);
//             }
//         }

//         DB::commit();

//         return back()->with('success', 'Attendance saved & OTP SMS sent');

//     } catch (\Exception $e) {

//         DB::rollBack();

//         return back()->with('error', $e->getMessage());
//     }
// }

// public function bulkSave(Request $request)
// {
//     // dd($request->all());
//     $request->validate([
//         'date'       => 'required|date|before_or_equal:today',
//         'department' => 'required',
//         'section'    => 'required',
//         'year'       => 'required',
//     ]);

//     DB::beginTransaction();

//     try {

//         $currentYear = now()->year;

//         // 1️⃣ Get all students in selected filter
//         $students = Student::where('department_id', $request->department)
//             ->where('section_id', $request->section)
//             ->whereRaw("(? - admission_year + 1) = ?", [
//                 $currentYear,
//                 (int)$request->year
//             ])
//             ->get();

//         // 2️⃣ Selected students = Absent
//         $absentIds = $request->students ?? [];

//         foreach ($students as $student) {

//             // $status = in_array($student->id, $absentIds)
//             $status = in_array((string)$student->id, $absentIds)

//                 ? 'A'
//                 : 'P';

//             Attendance::updateOrCreate(
//                 [
//                     'student_id' => $student->id,
//                     'date'       => $request->date
//                 ],
//                 [
//                     'status' => $status
//                 ]
//             );

//             // 3️⃣ Send SMS only for absent
//             if ($status === 'A' && !empty($student->father_phone)) {

//                 $otp = rand(100000, 999999);
//                 $message = "Your child is absent today. OTP: {$otp}";

//                 SmsService::send($student->father_phone, $message);
//             }
//         }

//         DB::commit();

//         return back()->with('success', 'Attendance saved successfully');

//     } catch (\Exception $e) {

//         DB::rollBack();

//         return back()->with('error', $e->getMessage());
//     }
// }
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
