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


        // return view('admin.attendance.partials.students', [
        //     'students' => $students,
        //     'attendanceExists' => $attendanceExists
        // ]);
        $html = view('admin.attendance.partials.students', [
            'students' => $students,
            'attendanceExists' => $attendanceExists
        ])->render();

        return response()->json([
            'html' => $html,
            'attendanceExists' => $attendanceExists,
        ]);
    }


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
            'date' => 'required|date|before_or_equal:today',
            'department' => 'required',
            'section' => 'required',
            'year' => 'required|integer|between:1,4',
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
                ->map(fn($id) => (int) $id)
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

                // $otp = null;

                // if ($status === 'A') {
                //     $otp = rand(100000, 999999);
                // }


                /*
                |--------------------------------------------------------------------------
                | CREATE attendance
                |--------------------------------------------------------------------------
                */

                // $attendance = Attendance::create([
                //     'student_id' => $student->id,
                //     'date' => $request->date,
                //     'status' => $status,
                //     'otp' => $otp,
                // ]);
                $attendance = Attendance::create([
                    'student_id' => $student->id,
                    'date' => $request->date,
                    'status' => $status,
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

                // if (
                //     $status === 'A' &&
                //     !empty($student->father_phone)
                // ) {

                //     $message =
                //         "{$otp} Please use this OTP {$otp} for your registration.IDLSMS";

                //     SmsService::send(
                //         $student->father_phone,
                //         $message
                //     );
                // }
                /*
|--------------------------------------------------------------------------
| DLT ABSENCE SMS
|--------------------------------------------------------------------------
*/

if (
    $status === 'A' &&
    !empty($student->father_phone)
) {

    $date = \Carbon\Carbon::parse($request->date)
        ->format('d-m-Y');

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | Keep this text exactly matching the DLT-approved template.
    |--------------------------------------------------------------------------
    */

    $message =
        "Dear Parent,\n" .
        "your ward Name:{$student->name} is absent on Date:{$date}\n" .
        "TSACBCON\n" .
        "Principal\n" .
        "IDEAL";

    Log::info('ABSENCE DLT SMS', [
        'student_id' => $student->id,
        'student_name' => $student->name,
        'phone' => $student->father_phone,
        'date' => $date,
        'template_id' => config('services.sms.te_id'),
    ]);

    SmsService::send(
        $student->father_phone,
        $message,
        config('services.sms.te_id')
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
    //     Log::info('========== DAY ATTENDANCE UPDATE ==========', [
    //         'user_id' => auth()->id(),
    //         'student_id' => $request->student_id,
    //         'date' => $request->date,
    //         'status' => $request->status,
    //     ]);
    //     DB::beginTransaction();

    //     try {

    //         $attendance = Attendance::updateOrCreate(
    //             [
    //                 'student_id' => $request->student_id,
    //                 'date' => $request->date
    //             ],
    //             [
    //                 'status' => $request->status
    //             ]
    //         );
    //         Log::info('DAY ATTENDANCE SAVED', [
    //             'attendance_id' => $attendance->id,
    //             'student_id' => $request->student_id,
    //             'date' => $request->date,
    //             'status' => $request->status,
    //             'user_id' => auth()->id(),
    //         ]);
    //         // If Absent → Generate OTP + Send SMS
    //         if ($request->status === 'A') {

    //             $student = Student::find($request->student_id);

    //             if ($student && !empty($student->father_phone)) {

    //                 $otp = rand(100000, 999999);

    //                 // Save OTP
    //                 $attendance->update([
    //                     'otp' => $otp
    //                 ]);

    //                 //  message || ${otp} Please use this OTP ${otp} for your registration.IDLSMS,
    //                 $message = "{$otp} Please use this OTP {$otp} for your registration.IDLSMS";
    //                 // $message = "Please use this OTP {$otp} for absence confirmation. IDLSMS";

    //                 SmsService::send($student->father_phone, $message);
    //             }
    //         }

    //         DB::commit();

    //         return back()->with('success', 'Attendance Updated');

    //     } catch (\Exception $e) {
    //         Log::error('DAY ATTENDANCE FAILED', [
    //             'student_id' => $request->student_id,
    //             'date' => $request->date,
    //             'status' => $request->status,
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //         ]);
    //         DB::rollBack();

    //         return back()->with('error', $e->getMessage());
    //     }


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
                'date' => $request->date,
            ],
            [
                'status' => $request->status,
            ]
        );

        Log::info('DAY ATTENDANCE SAVED', [
            'attendance_id' => $attendance->id,
            'student_id' => $request->student_id,
            'date' => $request->date,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | ABSENT → SEND DLT ABSENCE SMS
        |--------------------------------------------------------------------------
        */

        if ($request->status === 'A') {

            $student = Student::find($request->student_id);

            if ($student && !empty($student->father_phone)) {

                $date = \Carbon\Carbon::parse($request->date)
                    ->format('d-m-Y');

                /*
                |--------------------------------------------------------------------------
                | DLT APPROVED MESSAGE
                |--------------------------------------------------------------------------
                */

                $message =
                    "Dear Parent,\n" .
                    "your ward Name:{$student->name} is absent on Date:{$date}\n" .
                    "TSACBCON\n" .
                    "Principal\n" .
                    "IDEAL";

                Log::info('DAY ABSENCE DLT SMS', [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'phone' => $student->father_phone,
                    'date' => $date,
                    'template_id' => config('services.sms.te_id'),
                ]);

                $smsResult = SmsService::send(
                    $student->father_phone,
                    $message,
                    config('services.sms.te_id')
                );

                Log::info('DAY ABSENCE SMS RESULT', [
                    'student_id' => $student->id,
                    'phone' => $student->father_phone,
                    'result' => $smsResult,
                ]);
            } else {

                Log::warning('DAY ABSENCE SMS NOT SENT - NO PHONE', [
                    'student_id' => $request->student_id,
                ]);
            }
        }

        DB::commit();

        return back()->with(
            'success',
            'Attendance Updated'
        );

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error('DAY ATTENDANCE FAILED', [
            'student_id' => $request->student_id,
            'date' => $request->date,
            'status' => $request->status,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return back()->with(
            'error',
            $e->getMessage()
        );
    }
}
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
        } else {

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
        $absentCount = 0;
        $notMarked = 0;


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

            'students' => $students,

            'date' => $date,

            'presentCount' => $presentCount,

            'absentCount' => $absentCount,

            'notMarked' => $notMarked,

            'departments' => Department::orderBy('name')->get(),

            'sections' => $request->department
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
        $year = $request->year ?? now()->year;

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
