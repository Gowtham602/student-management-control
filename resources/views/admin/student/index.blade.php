@extends('layouts.admin')

@section('title','Students')

@section('content')

<div class="bg-white rounded-xl border shadow-sm">

    {{-- Header --}}
    <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-lg font-semibold text-gray-800">
            Students List
        </h2>

        <a href="{{ route('admin.students.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2
                  bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + Add Student
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Department</th>
                    <th class="px-6 py-3 text-left">Phone</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($students as $student)
                <tr id="student-row-{{ $student->id }}" class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">
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
                    <!-- <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.students.edit',$student->id) }}"
                           class="text-indigo-600 hover:underline">
                            Edit
                        </a>

                        <button onclick="deleteStudent({{ $student->id }})"
                                class="text-red-600 hover:underline">
                            Delete
                        </button>
                    </td> -->

                    <!-- other tds -->

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-4">

                            <!-- Edit -->
                            <a href="{{ route('admin.students.edit', $student->id) }}"
                                class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition">

                                <!-- Pencil Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z" />
                                </svg>

                                <span>Edit</span>
                            </a>

                            <!-- Delete -->
                            <button onclick="deleteStudent({{ $student->id }})"
                                class="flex items-center gap-1 text-red-600 hover:text-red-800 transition">

                                <!-- Trash Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H9V5a1 1 0 011-1z" />
                                </svg>

                                <span>Delete</span>
                            </button>

                        </div>
                    </td>



                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
    function deleteStudent(id) {

        Swal.fire({
            title: 'Are you sure?',
            text: 'This student record will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: `/admin/students/${id}`,
                    type: 'DELETE',

                    success: function(res) {

                        // Remove row
                        $('#student-row-' + id).fadeOut(300, function() {
                            $(this).remove();
                        });

                        // Toast
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Student deleted successfully',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    },

                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Something went wrong. Try again.'
                        });
                    }
                });
            }
        });
    }
</script>
@endpush

@endsection