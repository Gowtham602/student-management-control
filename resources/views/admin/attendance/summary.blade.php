@extends('layouts.admin')
@section('title','Attendance Summary')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-gray-800">
            Attendance Summary
        </h2>

        <form method="GET" class="flex flex-wrap gap-3">
            <select name="month" class="border rounded-lg px-3 py-2 text-sm">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" @selected($month==$m)>
                        {{ date('F', mktime(0,0,0,$m,1)) }}
                    </option>
                @endforeach
            </select>

            <select name="year" class="border rounded-lg px-3 py-2 text-sm">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" @selected($year==$y)>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
                Filter
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
                    <th class="px-4 py-3 text-center">Present</th>
                    <th class="px-4 py-3 text-center">Absent</th>
                    <th class="px-4 py-3 text-center">Holiday</th>
                    <th class="px-4 py-3 text-center">Attendance %</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($students as $student)

                    @php
                        $attendancePercent = $totalDays > 0
                            ? round(($student->present_days / $totalDays) * 100, 2)
                            : 0;

                        $percentColor =
                            $attendancePercent >= 90 ? 'bg-green-100 text-green-700' :
                            ($attendancePercent >= 75 ? 'bg-yellow-100 text-yellow-700' :
                            'bg-red-100 text-red-700');
                    @endphp

                    <tr class="hover:bg-indigo-50 transition">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium">{{ $student->rollnum }}</td>
                        <td class="px-4 py-3">{{ $student->name }}</td>

                        <td class="px-4 py-3 text-center text-green-700 font-semibold">
                            {{ $student->present_days }}
                        </td>

                        <td class="px-4 py-3 text-center text-red-600 font-semibold">
                            {{ $student->absent_days }}
                        </td>

                        <td class="px-4 py-3 text-center text-yellow-600 font-semibold">
                            {{ $student->holiday_days }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $percentColor }}">
                                {{ $attendancePercent }}%
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
