@forelse($students as $student)
<tr class="hover:bg-indigo-50">
    <td class="px-4 py-3 text-center">
        <input type="checkbox"
               name="students[]"
               value="{{ $student->id }}"
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
