@extends('layouts.admin')

@section('title','Admin Dashboard')

@section('content')

{{-- HERO / BANNER --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-500 p-8 text-white mb-8">
    <div class="relative z-10">
        <h1 class="text-3xl font-semibold tracking-tight">
            Welcome back, {{ auth()->user()->name }}
        </h1>
        <p class="mt-2 text-indigo-100 max-w-xl">
            Manage students, teachers, departments and attendance
            from one centralized dashboard.
        </p>

        <div class="flex gap-8 mt-6">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <!-- Students icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 14l6.16-3.422A12.083 12.083 0 0112 21
                                 c-2.28 0-4.42-.62-6.16-1.422L12 14z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-indigo-100">Students</p>
                    <p class="text-xl font-bold">120</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <!-- Teacher icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 12a5 5 0 100-10 5 5 0 000 10z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-indigo-100">Teachers</p>
                    <p class="text-xl font-bold">15</p>
                </div>
            </div>
        </div>
    </div>

    {{-- subtle background decoration --}}
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top,_white,_transparent_60%)]"></div>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <p class="text-sm text-gray-500">Total Students</p>
        <p class="mt-2 text-3xl font-semibold text-gray-900">120</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <p class="text-sm text-gray-500">Total Teachers</p>
        <p class="mt-2 text-3xl font-semibold text-gray-900">15</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <p class="text-sm text-gray-500">Attendance Today</p>
        <p class="mt-2 text-3xl font-semibold text-emerald-600">92%</p>
    </div>

</div>

{{-- ACTION + STATUS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            Quick Actions
        </h3>

        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.students.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
               bg-indigo-600 text-white hover:bg-indigo-700 transition">
                ➕ Add Student
            </a>

          
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">
            System Status
        </h3>
        <p class="text-sm text-gray-500 flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
            All services running normally
        </p>
    </div>

</div>

@endsection
