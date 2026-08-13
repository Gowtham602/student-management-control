@if($attendanceExists)

    <tr>
        <td colspan="7" class="text-center py-6 text-red-500 font-semibold">
            Attendance is Locked for this Date
        </td>
    </tr>

@else

    @forelse($students as $student)

        @php
            // Semester → Year
            $yearLabel = $student->study_year;

            $attendance = $student->attendances->first();
        @endphp

        <tr class="hover:bg-indigo-50">

            {{-- Checkbox --}}
            <td class="px-4 py-3 text-center">
                <input
                    type="checkbox"
                    value="{{ $student->id }}"
                    data-name="{{ $student->name }}"
                    data-department="{{ $student->department->name ?? '-' }}"
                    data-year="{{ $yearLabel }}"
                    class="student-check w-4 h-4"
                >
            </td>


            {{-- Roll Number --}}
            <td class="px-4 py-3">
                {{ $student->rollnum }}
            </td>


            {{-- Name --}}
            <td class="px-4 py-3">
                {{ $student->name }}
            </td>


            {{-- Year --}}
            <td class="px-4 py-3">
                {{ $yearLabel }}
            </td>


            {{-- Department --}}
            <td class="px-4 py-3">
                {{ $student->department->name ?? '-' }}
            </td>


            {{-- Section --}}
            <td class="px-4 py-3">
                {{ $student->section->name ?? '-' }}
            </td>


            {{-- Attendance Status --}}
            <td class="px-4 py-3 text-center">

                @if($attendance)

                    @if($attendance->status === 'A')

                        <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded">
                            Absent
                        </span>

                    @elseif($attendance->status === 'P')

                        <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded">
                            Present
                        </span>

                    @endif

                @else

                    <span class="text-gray-400 text-xs">
                        Not Marked
                    </span>

                @endif

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="7" class="text-center py-4 text-gray-500">
                No students found
            </td>
        </tr>

    @endforelse

@endif