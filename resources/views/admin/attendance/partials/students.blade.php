@if($attendanceExists)

<tr>
    <td colspan="7" class="text-center py-6 text-red-500 font-semibold">
        Attendance is Locked for this Date
    </td>
</tr>

@else

    @forelse($students as $student)

        @php
    $now = now();
    $academicYear = ($now->month >= 7) ? $now->year : $now->year - 1;

    $yearLevel = $academicYear - $student->admission_year + 1;

    if ($yearLevel >= 1 && $yearLevel <= 4) {
        $yearLabel = $yearLevel . ' Year';
    } elseif ($yearLevel > 4) {
        $yearLabel = 'Passout';
    } else {
        $yearLabel = 'Not Started';
    }

    $attendance = $student->attendances->first();
@endphp

        <tr class="hover:bg-indigo-50">
            <td class="px-4 py-3 text-center">
                <input type="checkbox"
                    value="{{ $student->id }}"
                    data-name="{{ $student->name }}"
                    data-department="{{ $student->department->name ?? '-' }}"
                    data-year="{{ $yearLabel }}"
                    class="student-check w-4 h-4">
            </td>

            <td class="px-4 py-3">{{ $student->rollnum }}</td>
            <td class="px-4 py-3">{{ $student->name }}</td>
            <td class="px-4 py-3">{{ $yearLabel }}</td>
            <td class="px-4 py-3">{{ $student->department->name ?? '-' }}</td>
            <td class="px-4 py-3">{{ $student->section->name ?? '-' }}</td>

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
                    <span class="text-gray-400 text-xs">Not Marked</span>
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
