@extends('layouts.admin')
@section('title','Student Day Attendance')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800">
            Student Day Attendance
        </h2>

        <form method="GET" class="flex items-center gap-3">
            <input type="date" name="date" value="{{ $date }}"
                   class="border rounded-lg px-3 py-2 text-sm">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
                View
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow border overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3">S.No</th>
                    <th class="px-4 py-3">Roll No</th>
                    <th class="px-4 py-3">Student Name</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($students as $student)
                    @php
                        $attendance = $student->attendances->first();
                        $status = $attendance->status ?? '-';
                    @endphp

                    <tr class="hover:bg-indigo-50 transition">
                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $student->rollnum }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $student->name }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $status === 'P' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $status === 'A' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $status === 'H' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $status === '-' ? 'bg-gray-100 text-gray-500' : '' }}">
                                {{ $status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            No students found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
