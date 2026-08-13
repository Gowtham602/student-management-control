<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Student, Attendance, Department, Section};
use Illuminate\Http\Request;
use App\Services\SmsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Bulk Attendance Page
    |--------------------------------------------------------------------------
    */
//  public function index(Request $request)
// {
//     // $currentYear = now()->year;
//     $now = now();
//     $currentYear = ($now->month >= 7) ? $now->year : $now->year - 1;
//     $date = $request->date ?? now()->toDateString();

//     $students = collect();
//     $attendanceExists = false;

//     // Load students only if filters selected
//     if ($request->filled(['department','section','year'])) {

//         $students = Student::with([
//                 'department',
//                 'section',
//                 'attendances' => function ($q) use ($date) {
//                     $q->whereDate('date', $date);
//                 }
//             ])
//             ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])
//             ->where('passout_year', '>=', $currentYear)
//             ->where('department_id', $request->department)
//             ->where('section_id', $request->section)
//             ->whereRaw("(? - admission_year + 1) = ?", [
//                 $currentYear,
//                 (int)$request->year
//             ])
//             ->when($request->search, function ($q) use ($request) {
//                 $q->where(function ($sub) use ($request) {
//                     $sub->where('name', 'like', "%{$request->search}%")
//                         ->orWhere('rollnum', 'like', "%{$request->search}%");
//                 });
//             })
//             ->orderBy('rollnum')
//             ->get();

//         // Check if attendance already marked
//     if ($students->isNotEmpty()) {

//     $attendanceExists = Attendance::whereDate('date', $date)
//         ->whereIn('student_id', $students->pluck('id'))
//         ->count() >= $students->count();

// }

//     }

//     return view('admin.attendance.index', [
//         'students'         => $students,
//         'date'             => $date,
//         'departments'      => Department::orderBy('name')->get(),
//         'sections'         => Section::orderBy('name')->get(),
//         'attendanceExists' => $attendanceExists,
//     ]);
// }


public function index(Request $request)
{

    Log::info('ATTENDANCE BULK PAGE', [
        'user_id' => auth()->id(),
        'date' => $request->date,
        'department' => $request->department,
        'section' => $request->section,
        'year' => $request->year,
        'search' => $request->search,
    ]);
    $date = $request->date ?? now()->toDateString();

    $students = collect();
    $attendanceExists = false;


    // Load students only when filters are selected
    if ($request->filled(['department', 'section', 'year'])) {

        $year = (int) $request->year;

        // Get semester range
        $semesterRange = $this->semesterRangeForYear($year);

        if (!$semesterRange) {
            return back()->with(
                'error',
                'Invalid year selected.'
            );
        }


        $students = Student::with([
            'department',
            'section',
            'attendances' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            }
        ])

        ->where('department_id', $request->department)

        ->where('section_id', $request->section)

        // IMPORTANT
        ->whereBetween('semester', $semesterRange)

        // Search
        ->when($request->search, function ($q) use ($request) {

            $q->where(function ($sub) use ($request) {

                $sub->where(
                    'name',
                    'like',
                    "%{$request->search}%"
                )
                ->orWhere(
                    'rollnum',
                    'like',
                    "%{$request->search}%"
                );

            });

        })

        ->orderBy('rollnum')
        ->get();


        // Check attendance
        if ($students->isNotEmpty()) {

            $attendanceExists = Attendance::whereDate(
                'date',
                $date
            )
            ->whereIn(
                'student_id',
                $students->pluck('id')
            )
            ->count() >= $students->count();
        }
    }


    return view('admin.attendance.index', [

        'students' => $students,

        'date' => $date,

        'departments' => Department::orderBy('name')->get(),

        'sections' => Section::orderBy('name')->get(),

        'attendanceExists' => $attendanceExists,
    ]);
}
private function semesterRangeForYear(int $year): ?array
{
    return match ($year) {
        1 => [1, 2],
        2 => [3, 4],
        3 => [5, 6],
        4 => [7, 8],
        default => null,
    };
}

    // section for drop in search 
    public function sections($departmentId)
    {
        return Section::where('department_id', $departmentId)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

  

// public function ajaxStudents(Request $request)
// {
//     // $currentYear = now()->year;
//     $now = now();
// $currentYear = ($now->month >= 7) ? $now->year : $now->year - 1;
//     $date = $request->date ?? now()->toDateString();

//     $students = collect();
//     $attendanceExists = false;

//     if ($request->filled(['department','section','year'])) {

//         $students = Student::with([
//             'department',
//             'section',
//             'attendances' => function ($q) use ($date) {
//                 $q->whereDate('date', $date);
//             }
//         ])
//         ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])   // ADD THIS
//         ->where('passout_year', '>=', $currentYear) 
//         ->where('department_id', $request->department)
//         ->where('section_id', $request->section)
//         ->whereRaw("(? - admission_year + 1) = ?", [
//             $currentYear,
//             (int)$request->year
//         ])

//         // search 
//         ->when($request->search, function ($q) use ($request) {
//             $q->where(function ($sub) use ($request) {
//                 $sub->where('name', 'like', "%{$request->search}%")
//                     ->orWhere('rollnum', 'like', "%{$request->search}%");
//             });
//         })
//         ->orderBy('rollnum')
//         ->get();

//        if ($students->isNotEmpty()) {

//     $attendanceExists = Attendance::whereDate('date', $date)
//         ->whereIn('student_id', $students->pluck('id'))
//         ->count() >= $students->count();

// }
//     }

//     return view('admin.attendance.partials.students', [
//         'students' => $students,
//         'attendanceExists' => $attendanceExists
//     ]);
// }


public function ajaxStudents(Request $request)
{
    Log::info('ATTENDANCE AJAX STUDENTS', [
        'user_id' => auth()->id(),
        'date' => $request->date,
        'department' => $request->department,
        'section' => $request->section,
        'year' => $request->year,
        'search' => $request->search,
    ]);
    $date = $request->date ?? now()->toDateString();

    $students = collect();
    $attendanceExists = false;

    if ($request->filled(['department', 'section', 'year'])) {

        $year = (int) $request->year;

        // Get semester range for selected year
        $semesterRange = $this->semesterRangeForYear($year);

        if (!$semesterRange) {
            return view('admin.attendance.partials.students', [
                'students' => collect(),
                'attendanceExists' => false,
            ]);
        }

        $students = Student::with([
            'department',
            'section',
            'attendances' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            }
        ])
        
        ->where('department_id', $request->department)
        ->where('section_id', $request->section)

        // IMPORTANT
        // Year 1 = Semester 1,2
        // Year 2 = Semester 3,4
        // Year 3 = Semester 5,6
        // Year 4 = Semester 7,8
        ->whereBetween('semester', $semesterRange)

        // Search
        ->when($request->search, function ($q) use ($request) {

            $q->where(function ($sub) use ($request) {

                $sub->where(
                    'name',
                    'like',
                    "%{$request->search}%"
                )
                ->orWhere(
                    'rollnum',
                    'like',
                    "%{$request->search}%"
                );

            });

        })

        ->orderBy('rollnum')
        ->get();
Log::info('ATTENDANCE AJAX STUDENTS RESULT', [
    'count' => $students->count(),
    'student_ids' => $students->pluck('id')->toArray(),
]);

        // Check whether attendance already exists
        if ($students->isNotEmpty()) {

            $attendanceExists = Attendance::whereDate('date', $date)
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->count() >= $students->count();
        }
    }


    return view('admin.attendance.partials.students', [
        'students' => $students,
        'attendanceExists' => $attendanceExists
    ]);
}
    
// public function bulkSave(Request $request)
// {
//     $request->validate([
//         'date'       => 'required|date|before_or_equal:today',
//         'department' => 'required',
//         'section'    => 'required',
//         'year'       => 'required',
//     ]);

//     DB::beginTransaction();

//     try {

//         // $currentYear = now()->year;
//         $now = now();
//         $currentYear = ($now->month >= 7) ? $now->year : $now->year - 1;
//         $alreadyMarked = Attendance::where('date', $request->date)

//             ->whereIn('student_id', function ($q) use ($request, $currentYear) {
//                 $q->select('id')
//                 ->from('students')
//                 ->where('department_id', $request->department)
//                 ->where('section_id', $request->section)
//                 ->whereRaw("(? - admission_year + 1) = ?", [
//                     $currentYear,
//                     (int)$request->year
//                 ]);
//             })
//         ->exists();

//         if ($alreadyMarked) {
//             return back()->with('error', 'Attendance already marked for this date.');
//         }


//         $students = Student::where('department_id', $request->department)
//             ->where('section_id', $request->section)
//             ->whereRaw("(? - admission_year + 1) = ?", [
//                 $currentYear,
//                 (int)$request->year
//             ])
//             ->get();

//         // Convert to integer array
//         $absentIds = collect($request->students ?? [])
//                         ->map(fn($id) => (int)$id)
//                         ->toArray();

//         // foreach ($students as $student) {

//         //     $status = in_array($student->id, $absentIds)
//         //         ? 'A'
//         //         : 'P';

//         //     Attendance::updateOrCreate(
//         //         [
//         //             'student_id' => $student->id,
//         //             'date'       => $request->date
//         //         ],
//         //         [
//         //             'status' => $status
//         //         ]
//         //     );
//         // }
//         foreach ($students as $student) {

//     $status = in_array($student->id, $absentIds)
//         ? 'A'
//         : 'P';

//     $otp = null;

//     if ($status === 'A') {
//         $otp = rand(100000, 999999);
//     }

//     $attendance = Attendance::updateOrCreate(
//         [
//             'student_id' => $student->id,
//             'date'       => $request->date
//         ],
//         [
//             'status' => $status,
//             'otp'    => $otp
//         ]
//     );

//     // Send SMS only if Absent
//     if ($status === 'A' && !empty($student->father_phone)) {

//         // $message = "Please use this OTP {$otp} for absence confirmation. IDLSMS";
//         $message = "{$otp} Please use this OTP {$otp} for your registration.IDLSMS";

//         SmsService::send($student->father_phone, $message);
//     }
// }


        

//         DB::commit();

//         return back()->with('success', 'Attendance saved successfully');

//     } catch (\Exception $e) {

//         DB::rollBack();

//         return back()->with('error', $e->getMessage());
//     }
// }

 


// public function bulkSave(Request $request)
// {
//     //    dd($request->all());
//     $request->validate([
//         'date'       => 'required|date|before_or_equal:today',
//         'department' => 'required',
//         'section'    => 'required',
//         'year'       => 'required|integer|between:1,4',
//     ]);

//     DB::beginTransaction();

//     try {

//         // Year 1 = Semester 1,2
//         // Year 2 = Semester 3,4
//         // Year 3 = Semester 5,6
//         // Year 4 = Semester 7,8

//         $semesterRange = $this->semesterRangeForYear(
//             (int) $request->year
//         );

//         if (!$semesterRange) {
//             return back()->with(
//                 'error',
//                 'Invalid year selected.'
//             );
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Get students
//         |--------------------------------------------------------------------------
//         */

//         $students = Student::where(
//                 'department_id',
//                 $request->department
//             )
//             ->where(
//                 'section_id',
//                 $request->section
//             )
//             ->whereBetween(
//                 'semester',
//                 $semesterRange
//             )
//             ->orderBy('rollnum')
//             ->get();

// // dd([
// //     'students_found' => $students->pluck('id', 'rollnum'),
// //     'selected_absent' => $request->students,
// // ]);

//         /*
//         |--------------------------------------------------------------------------
//         | No students found
//         |--------------------------------------------------------------------------
//         */

//         if ($students->isEmpty()) {

//             DB::rollBack();

//             return back()->with(
//                 'error',
//                 'No students found for selected Year / Department / Section.'
//             );
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Check attendance already marked
//         |--------------------------------------------------------------------------
//         */

//         $alreadyMarked = Attendance::whereDate(
//                 'date',
//                 $request->date
//             )
//             ->whereIn(
//                 'student_id',
//                 $students->pluck('id')
//             )
//             ->exists();


//         if ($alreadyMarked) {

//             DB::rollBack();

//             return back()->with(
//                 'error',
//                 'Attendance already marked for this date.'
//             );
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Selected students = ABSENT
//         |--------------------------------------------------------------------------
//         */

//         $absentIds = collect(
//                 $request->students ?? []
//             )
//             ->map(fn ($id) => (int) $id)
//             ->toArray();


//         /*
//         |--------------------------------------------------------------------------
//         | Save attendance
//         |--------------------------------------------------------------------------
//         */

//         foreach ($students as $student) {

//             $status = in_array(
//                 $student->id,
//                 $absentIds
//             )
//                 ? 'A'
//                 : 'P';


//             $otp = null;


//             // Generate OTP only for absent students
//             if ($status === 'A') {

//                 $otp = rand(100000, 999999);
//             }


//             Attendance::updateOrCreate(
//                 [
//                     'student_id' => $student->id,
//                     'date'       => $request->date,
//                 ],
//                 [
//                     'status' => $status,
//                     'otp'    => $otp,
//                 ]
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | Send SMS only for absent students
//             |--------------------------------------------------------------------------
//             */

//             if (
//                 $status === 'A' &&
//                 !empty($student->father_phone)
//             ) {

//                 $message =
//                     "{$otp} Please use this OTP {$otp} for your registration.IDLSMS";

//                 SmsService::send(
//                     $student->father_phone,
//                     $message
//                 );
//             }
//         }


//         DB::commit();


//         return back()->with(
//             'success',
//             'Attendance saved successfully'
//         );


//     } catch (\Throwable $e) {

//         DB::rollBack();

//         return back()->with(
//             'error',
//             $e->getMessage()
//         );
//     }
// }
public function bulkSave(Request $request)
{
     Log::info('========== BULK ATTENDANCE START ==========', [
        'user_id' => auth()->id(),
        'date' => $request->date,
        'department' => $request->department,
        'section' => $request->section,
        'year' => $request->year,
        'selected_students' => $request->students ?? [],
    ]);
    $request->validate([
        'date'       => 'required|date|before_or_equal:today',
        'department' => 'required',
        'section'    => 'required',
        'year'       => 'required|integer|between:1,4',
    ]);
    

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | Get semester range
        |--------------------------------------------------------------------------
        */

        $semesterRange = $this->semesterRangeForYear(
            (int) $request->year
        );

        if (!$semesterRange) {

            DB::rollBack();

            return back()->with(
                'error',
                'Invalid year selected.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get students
        |--------------------------------------------------------------------------
        */

        $students = Student::where(
                'department_id',
                $request->department
            )
            ->where(
                'section_id',
                $request->section
            )
            ->whereBetween(
                'semester',
                $semesterRange
            )
            ->orderBy('rollnum')
            ->get();

Log::info('BULK ATTENDANCE STUDENTS FOUND', [
    'count' => $students->count(),
    'student_ids' => $students->pluck('id')->toArray(),
]);
        if ($students->isEmpty()) {

            DB::rollBack();

            return back()->with(
                'error',
                'No students found for selected Year / Department / Section.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Selected students = ABSENT
        |--------------------------------------------------------------------------
        */

        $absentIds = collect(
            $request->students ?? []
        )
            ->map(fn ($id) => (int) $id)
            ->toArray();
Log::info('BULK ATTENDANCE ABSENT IDS', [
    'absent_ids' => $absentIds,
]);

        /*
        |--------------------------------------------------------------------------
        | Save attendance
        |--------------------------------------------------------------------------
        */

        foreach ($students as $student) {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | If Day Attendance already marked this student,
            | don't overwrite it.
            |--------------------------------------------------------------------------
            */

            $existingAttendance = Attendance::where(
                'student_id',
                $student->id
            )
                ->whereDate(
                    'date',
                    $request->date
                )
                ->first();


            // if ($existingAttendance) {
            //     continue;
            // }
if ($existingAttendance) {

    Log::warning('BULK ATTENDANCE SKIPPED - ALREADY EXISTS', [
        'student_id' => $student->id,
        'rollnum' => $student->rollnum,
        'date' => $request->date,
        'existing_status' => $existingAttendance->status,
    ]);

    continue;
}

            /*
            |--------------------------------------------------------------------------
            | Checkbox selected = ABSENT
            | Not selected = PRESENT
            |--------------------------------------------------------------------------
            */

            $status = in_array(
                $student->id,
                $absentIds
            )
                ? 'A'
                : 'P';
Log::info('BULK ATTENDANCE STATUS', [
    'student_id' => $student->id,
    'rollnum' => $student->rollnum,
    'student_name' => $student->name,
    'status' => $status,
]);

            /*
            |--------------------------------------------------------------------------
            | OTP for absent
            |--------------------------------------------------------------------------
            */

            $otp = null;

            if ($status === 'A') {
                $otp = rand(100000, 999999);
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE attendance
            |--------------------------------------------------------------------------
            */

           $attendance= Attendance::create([
                'student_id' => $student->id,
                'date'       => $request->date,
                'status'     => $status,
                'otp'        => $otp,
            ]);
Log::info('BULK ATTENDANCE SAVED', [
    'attendance_id' => $attendance->id,
    'student_id' => $student->id,
    'rollnum' => $student->rollnum,
    'status' => $status,
    'date' => $request->date,
]);

            /*
            |--------------------------------------------------------------------------
            | SMS for absent
            |--------------------------------------------------------------------------
            */

            if (
                $status === 'A' &&
                !empty($student->father_phone)
            ) {

                $message =
                    "{$otp} Please use this OTP {$otp} for your registration.IDLSMS";

                SmsService::send(
                    $student->father_phone,
                    $message
                );
            }
        }

Log::info('========== BULK ATTENDANCE SUCCESS ==========', [
    'user_id' => auth()->id(),
    'date' => $request->date,
    'department' => $request->department,
    'section' => $request->section,
    'year' => $request->year,
]);
        DB::commit();


        return back()->with(
            'success',
            'Attendance saved successfully'
        );


    } catch (\Throwable $e) {

    DB::rollBack();

    Log::error('========== BULK ATTENDANCE FAILED ==========', [
        'user_id' => auth()->id(),
        'date' => $request->date,
        'department' => $request->department,
        'section' => $request->section,
        'year' => $request->year,
        'selected_students' => $request->students ?? [],
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ]);

    return back()->with(
        'error',
        $e->getMessage()
    );
}
}
// public function update(Request $request)
// {
//     $attendance = Attendance::updateOrCreate(
//         [
//             'student_id' => $request->student_id,
//             'date' => $request->date
//         ],
//         [
//             'status' => $request->status
//         ]
//     );

//     //  If Absent → Send OTP
//     if ($request->status == 'A') {

//         $otp = rand(100000, 999999);

//         $attendance->update([
//             'otp' => $otp
//         ]);

      
    
//     }

//     return back()->with('success', 'Attendance Updated');
// }

public function update(Request $request)
{
    Log::info('========== DAY ATTENDANCE UPDATE ==========', [
        'user_id' => auth()->id(),
        'student_id' => $request->student_id,
        'date' => $request->date,
        'status' => $request->status,
    ]);
    DB::beginTransaction();

    try {

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'date'       => $request->date
            ],
            [
                'status' => $request->status
            ]
        );
Log::info('DAY ATTENDANCE SAVED', [
    'attendance_id' => $attendance->id,
    'student_id' => $request->student_id,
    'date' => $request->date,
    'status' => $request->status,
    'user_id' => auth()->id(),
]);
        // If Absent → Generate OTP + Send SMS
        if ($request->status === 'A') {

            $student = Student::find($request->student_id);

            if ($student && !empty($student->father_phone)) {

                $otp = rand(100000, 999999);

                // Save OTP
                $attendance->update([
                    'otp' => $otp
                ]);

                //  message || ${otp} Please use this OTP ${otp} for your registration.IDLSMS,
                $message = "{$otp} Please use this OTP {$otp} for your registration.IDLSMS";
                // $message = "Please use this OTP {$otp} for absence confirmation. IDLSMS";

                SmsService::send($student->father_phone, $message);
            }
        }

        DB::commit();

        return back()->with('success', 'Attendance Updated');

    } catch (\Exception $e) {
Log::error('DAY ATTENDANCE FAILED', [
    'student_id' => $request->student_id,
    'date' => $request->date,
    'status' => $request->status,
    'error' => $e->getMessage(),
    'file' => $e->getFile(),
    'line' => $e->getLine(),
]);
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
//     $now = now();
//     $currentYear = ($now->month >= 7) ? $now->year : $now->year - 1;

//     $date = $request->date ?? now()->toDateString();

//     $query = Student::with([
//         'department',
//         'section',
//         'attendances' => function ($q) use ($date) {
//             $q->whereDate('date', $date);
//         }
//     ])
//     ->whereRaw("(? - admission_year + 1) BETWEEN 1 AND 4", [$currentYear])
//     ->where('passout_year','>=',$currentYear);

//     /* FILTER */
//     if ($request->filled(['department','section','year'])) {

//         $query->where('department_id',$request->department)
//               ->where('section_id',$request->section)
//               ->whereRaw("(? - admission_year + 1) = ?",[
//                     $currentYear,
//                     (int)$request->year
//               ]);
//     } 
//     else {
//         $query->whereRaw("1 = 0");
//     }

//     /* SEARCH */
//     if ($request->filled('search')) {

//         $query->where(function($q) use ($request){

//             $q->where('name','like','%'.$request->search.'%')
//               ->orWhere('rollnum','like','%'.$request->search.'%');

//         });
//     }

//     /* PAGINATION */
//     $students = (clone $query)
//                     ->orderBy('rollnum')
//                     ->paginate(15)
//                     ->withQueryString();

//     /* COUNTS */
//     $allStudents = (clone $query)->get();

//     $presentCount = 0;
//     $absentCount  = 0;
//     $notMarked    = 0;

//     foreach ($allStudents as $student) {

//         $attendance = $student->attendances->first();

//         if(!$attendance){
//             $notMarked++;
//         }
//         elseif($attendance->status == 'P'){
//             $presentCount++;
//         }
//         elseif($attendance->status == 'A'){
//             $absentCount++;
//         }
//     }

//     return view('admin.attendance.day',[
//         'students'=>$students,
//         'date'=>$date,
//         'presentCount'=>$presentCount,
//         'absentCount'=>$absentCount,
//         'notMarked'=>$notMarked,
//         'departments'=>Department::orderBy('name')->get(),
//         'sections'=>$request->department
//             ? Section::where('department_id',$request->department)->orderBy('name')->get()
//             : collect(),
//     ]);
// }

public function dayList(Request $request)
{
    $date = $request->date ?? now()->toDateString();

    $query = Student::with([
        'department',
        'section',
        'attendances' => function ($q) use ($date) {
            $q->whereDate('date', $date);
        }
    ]);

    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    if ($request->filled(['department', 'section', 'year'])) {

        $year = (int) $request->year;

        // Year -> Semester
        $semesterRange = $this->semesterRangeForYear($year);

        if (!$semesterRange) {
            return back()->with('error', 'Invalid year selected.');
        }

        $query->where('department_id', $request->department)
              ->where('section_id', $request->section)
              ->whereBetween('semester', $semesterRange);
    }
    else {

        // Don't show students until filters are selected
        $query->whereRaw('1 = 0');
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('name', 'like', "%{$search}%")
              ->orWhere('rollnum', 'like', "%{$search}%");

        });
    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    $students = (clone $query)
        ->orderBy('rollnum')
        ->paginate(15)
        ->withQueryString();

Log::info('DAY ATTENDANCE STUDENTS RESULT', [
    'count' => $students->count(),
    'student_ids' => $students->pluck('id')->toArray(),
]);
    /*
    |--------------------------------------------------------------------------
    | COUNTS
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    return view('admin.attendance.day', [

        'students'     => $students,

        'date'         => $date,

        'presentCount' => $presentCount,

        'absentCount'  => $absentCount,

        'notMarked'    => $notMarked,

        'departments'  => Department::orderBy('name')->get(),

        'sections'     => $request->department
            ? Section::where(
                'department_id',
                $request->department
            )
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
