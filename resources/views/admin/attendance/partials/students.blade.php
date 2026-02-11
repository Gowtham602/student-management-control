@forelse($students as $student)
<tr class="hover:bg-indigo-50">
    <td class="px-4 py-3 text-center">
        @php
    $yearLevel = now()->year - $student->admission_year + 1;
    $yearLabel = $yearLevel >= 1 && $yearLevel <= 4
        ? $yearLevel . ' Year'
        : 'Passout';
@endphp
        <input type="checkbox"
               name="students[]"
               value="{{ $student->id }}"
               data-name="{{ $student->name }}"
                data-department="{{ $student->department->name ?? '-' }}"
                 data-year="{{ $yearLabel }}"
               class="student-check w-4 h-4">
    </td>
    <td class="px-4 py-3">{{ $student->rollnum }}</td>
    <td class="px-4 py-3">{{ $student->name }}</td>
    <td class="px-4 py-3">
        {{ now()->year - $student->admission_year + 1 }} Year
    </td>
    <td class="px-4 py-3">{{ $student->department->name ?? '-' }}</td>
    <td class="px-4 py-3">{{ $student->section->name ?? '-' }}</td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-4 text-gray-500">
        No students found
    </td>
</tr>
@endforelse
