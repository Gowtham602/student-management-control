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

        // Basic counts
        $totalStudents = Student::count();
        $totalDepartments = Department::count();
        $totalSections = Section::count();

        $today = now()->toDateString();

        $absentStudents = Student::with(['department'])
            ->whereHas('attendances', function ($q) use ($today) {
                $q->whereDate('date', $today)
                ->where('status', 'A');
            })
            ->orderBy('rollnum')
            ->get();

        // Attendance counts
        $presentToday = Attendance::whereDate('date', $today)
                            ->where('status', 'P')
                            ->count();

        $absentToday = Attendance::whereDate('date', $today)
                            ->where('status', 'A')
                            ->count();

        $notMarked = $totalStudents - ($presentToday + $absentToday);

        $attendancePercentage = $totalStudents > 0
            ? round(($presentToday / $totalStudents) * 100)
            : 0;

        // Students by Year
        $currentYear = now()->year;

        $yearCounts = [
            '1st' => Student::whereRaw("? - admission_year + 1 = 1", [$currentYear])->count(),
            '2nd' => Student::whereRaw("? - admission_year + 1 = 2", [$currentYear])->count(),
            '3rd' => Student::whereRaw("? - admission_year + 1 = 3", [$currentYear])->count(),
            '4th' => Student::whereRaw("? - admission_year + 1 = 4", [$currentYear])->count(),
        ];

        // Recent Students
        $recentStudents = Student::latest()->take(5)->get();

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

