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
                <tr id="student-row-{{ $student->id }}"  class="hover:bg-gray-50">
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

                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.students.edit', $student->id) }}"
                            class="text-indigo-600 hover:underline">
                                Edit
                            </a>

                            <button onclick="deleteStudent({{ $student->id }})"
                                    class="text-red-600 hover:underline">
                                Delete
                            </button>
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

                success: function (res) {

                    // Remove row
                    $('#student-row-' + id).fadeOut(300, function () {
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

                error: function () {
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
