@extends('layouts.admin')

@section('title','Admin Dashboard')

@section('content')

{{-- HERO --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-cyan-500 p-8 text-white mb-8">
    <h1 class="text-3xl font-semibold">
        Welcome back, {{ auth()->user()->name }}
    </h1>
    <p class="mt-2 text-indigo-100">
        Manage students, departments and attendance from one centralized dashboard.
    </p>
</div>

{{-- MAIN STATS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Total Students</p>
        <p class="text-3xl font-bold text-indigo-600">{{ $totalStudents }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Departments</p>
        <p class="text-3xl font-bold text-indigo-600">{{ $totalDepartments }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Sections</p>
        <p class="text-3xl font-bold text-indigo-600">{{ $totalSections }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Attendance Today</p>
        <p class="text-3xl font-bold text-emerald-600">{{ $attendancePercentage }}%</p>
    </div>

</div>

{{-- ATTENDANCE BREAKDOWN --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-green-100 p-6 rounded-xl">
        <p class="text-sm">Present</p>
        <p class="text-2xl font-bold text-green-700">{{ $presentToday }}</p>
    </div>

    <div class="bg-red-100 p-6 rounded-xl">
        <p class="text-sm">Absent</p>
        <p class="text-2xl font-bold text-red-700">{{ $absentToday }}</p>
    </div>

    <div class="bg-yellow-100 p-6 rounded-xl">
        <p class="text-sm">Not Marked</p>
        <p class="text-2xl font-bold text-yellow-700">{{ $notMarked }}</p>
    </div>

</div>

{{-- STUDENTS BY YEAR --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4">Students by Year</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($yearCounts as $year => $count)
            <div class="bg-indigo-50 p-4 rounded-lg text-center">
                <p class="text-sm text-gray-600">{{ $year }}</p>
                <p class="text-xl font-bold text-indigo-700">{{ $count }}</p>
            </div>
        @endforeach
    </div>
</div>

{{-- TODAY ABSENT LIST --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">

    <h3 class="text-lg font-semibold mb-4 text-red-600">
        Today's Absent Students ({{ now()->format('d M Y') }})
    </h3>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-red-50 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Roll No</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Department</th>
                    <th class="px-4 py-3 text-left">Year</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($absentStudents as $student)

                    <!-- @php
                        $year = now()->year - $student->admission_year + 1;
                    @endphp -->
@php
    $now = now();
    $academicYear = ($now->month >= 7) ? $now->year : $now->year - 1;
    $year = $academicYear - $student->admission_year + 1;
@endphp
                    <tr class="hover:bg-red-50">

                        <td class="px-4 py-3 font-medium text-indigo-600">
                            {{ $student->rollnum }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $student->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $student->department->name ?? '-' }}
                        </td>

                        <td  class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                            <!-- {{ $year }} Year -->
                              {{ $student->study_year }}
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-400">
                            🎉 No Absent Students Today
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

@endsection