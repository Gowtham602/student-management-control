@extends('layouts.admin')

@section('title','Student Info')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow">

        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($student->name,0,1)) }}
            </div>  

            <div>
                <h2 class="text-2xl font-semibold">
                    {{ $student->name }}
                </h2>
                <p class="text-sm opacity-90">
                    Roll No: {{ $student->rollnum }} • {{ $student->department }}
                </p>
            </div>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-white rounded-2xl shadow p-6">

        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            Personal Information 
        </h3>

        <div class="grid md:grid-cols-2 gap-4 text-sm">

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Email</span>
                <span class="font-medium">{{ $student->email }}</span>
            </div>

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Phone</span>
                <span class="font-medium">{{ $student->phone }}</span>
            </div>

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Gender</span>
                <span class="font-medium">{{ ucfirst($student->gender) }}</span>
            </div>

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Blood Group</span>
                <span class="font-medium">{{ $student->blood_group ?? '-' }}</span>
            </div>

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Father Phone</span>
                <span class="font-medium">{{ $student->father_phone }}</span>
            </div>

        </div>
    </div>

    <!-- Academic Card -->
    <div class="bg-white rounded-2xl shadow p-6">

        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            Academic Details
        </h3>

        <div class="grid md:grid-cols-2 gap-4 text-sm">

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Department</span>
                <span class="font-medium">{{ $student->department }}</span>
            </div>

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Section</span>
                <span class="font-medium">{{ $student->section }}</span>
            </div>

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Academic Year</span>
                <span class="font-medium">{{ $student->academic_year }}</span>
            </div>

            <div class="flex justify-between border-b py-2">
                <span class="text-gray-500">Passout Year</span>
                <span class="font-medium">{{ $student->passout_year }}</span>
            </div>

        </div>
    </div>

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.students.index') }}"
           class="inline-flex items-center text-indigo-600 hover:underline font-medium">
            ← Back to Students
        </a>
    </div>

</div>

@endsection
