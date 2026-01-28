@extends('layouts.admin')

@section('title','Students')

@section('content')

<div class="bg-white rounded-xl border shadow-sm">

    <!-- Header -->
    <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-lg font-semibold text-gray-800">
            Students List
        </h2>

        <div class="flex gap-3">
            <a href="{{ route('admin.students.import.form') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Upload CSV
            </a>

            <a href="{{ route('admin.students.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                + Add Student
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left">S.No</th>
                    <th class="px-6 py-3 text-left">Roll No</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Department</th>
                    <th class="px-6 py-3 text-left">Phone</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($students as $student)
                <tr id="student-row-{{ $student->id }}" class="hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->rollnum }}
                    </td>

                    <td class="px-6 py-4 font-medium">
                        {{ $student->name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $student->email }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->department }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->phone }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-4">

                            <!-- Show info  -->
                            <!-- Info / View -->
                                <a href="{{ route('admin.students.show', $student->id) }}"
                                class="flex items-center gap-1 text-emerald-600 hover:text-emerald-800 transition">

                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 20
                                                a8 8 0 100-16 8 8 0 000 16z"/>
                                    </svg>
                                    Info
                                </a>

                            <!-- Edit -->
                            <a href="{{ route('admin.students.edit', $student->id) }}"
                               class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/>
                                </svg>
                                Edit
                            </a>

                            <!-- Delete -->
                            <button onclick="deleteStudent({{ $student->id }})"
                                    class="flex items-center gap-1 text-red-600 hover:text-red-800 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                             a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6"/>
                                </svg>
                                Delete
                            </button>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        No students found
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function deleteStudent(id) {

    Swal.fire({
        title: 'Delete student?',
        text: 'This record will be permanently removed.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: `/admin/students/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },

            success() {
                $('#student-row-' + id).fadeOut(300, function () {
                    $(this).remove();
                });

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Student deleted',
                    showConfirmButton: false,
                    timer: 2000
                });
            },

            error() {
                Swal.fire('Error', 'Delete failed', 'error');
            }
        });
    });
}
</script>
@endpush

@endsection
