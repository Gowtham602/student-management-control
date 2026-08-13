<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Department;
use App\Models\Section;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // -----------------------------------------
        // BASIC COUNTS
        // -----------------------------------------

        $totalStudents = Student::count();

        $totalDepartments = Department::count();

        $totalSections = Section::count();


        // -----------------------------------------
        // ABSENT STUDENTS TODAY
        // -----------------------------------------

        $absentStudents = Student::with('department')
            ->whereHas('attendances', function ($q) use ($today) {

                $q->whereDate('date', $today)
                  ->where('status', 'A');

            })
            ->orderBy('rollnum')
            ->get();


        // -----------------------------------------
        // ATTENDANCE COUNTS
        // -----------------------------------------

        $presentToday = Attendance::whereDate('date', $today)
            ->where('status', 'P')
            ->count();

        $absentToday = Attendance::whereDate('date', $today)
            ->where('status', 'A')
            ->count();


        $notMarked = max(
            0,
            $totalStudents - ($presentToday + $absentToday)
        );


        // -----------------------------------------
        // ATTENDANCE PERCENTAGE
        // -----------------------------------------

        $attendancePercentage = $totalStudents > 0
            ? round(($presentToday / $totalStudents) * 100)
            : 0;


        // -----------------------------------------
        // STUDENTS BY YEAR
        // -----------------------------------------

        $yearCounts = [

            '1st' => Student::whereIn('semester', [1, 2])->count(),

            '2nd' => Student::whereIn('semester', [3, 4])->count(),

            '3rd' => Student::whereIn('semester', [5, 6])->count(),

            '4th' => Student::whereIn('semester', [7, 8])->count(),

        ];


        // -----------------------------------------
        // RECENT STUDENTS
        // -----------------------------------------

        $recentStudents = Student::latest()
            ->take(5)
            ->get();


        // -----------------------------------------
        // RETURN DASHBOARD
        // -----------------------------------------

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalDepartments',
            'totalSections',
            'presentToday',
            'absentToday',
            'notMarked',
            'attendancePercentage',
            'yearCounts',
            'recentStudents',
            'absentStudents'
        ));
    }
}