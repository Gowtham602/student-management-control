@extends('layouts.admin')

@section('title','Students')

@section('content')

<div class="bg-white rounded-xl border shadow-sm">

    <!-- Header -->
<div class="flex items-center justify-between p-6 border-b gap-4">

    <!-- Title -->
    <h2 class="text-lg font-semibold text-gray-800 whitespace-nowrap">
        Students List
    </h2>

    <!-- Search -->
    <form method="GET"
          action="{{ route('admin.students.index') }}"
          class="flex-1 max-w-md">
        <div class="flex gap-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search name / email / roll no"
                class="border rounded-lg px-4 py-2 w-full focus:ring focus:ring-indigo-200"
            >

            <button
                type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                Search
            </button>
        </div>
    </form>

    <!-- Actions -->
     
    
    <div class="flex gap-3 whitespace-nowrap">
        
        <!-- ACTION BUTTONS -->
        <!-- <div class="flex gap-2 whitespace-nowrap"> -->
            <a href="{{ route('admin.students.export.excel') }}"
               class="bg-green-600 text-white px-3 py-2 rounded-lg">
                Excel
            </a>

            <a href="{{ route('admin.students.export.csv') }}"
               class="bg-blue-600 text-white px-3 py-2 rounded-lg">
                CSV
            </a>

            <a href="{{ route('admin.students.export.pdf') }}"
               class="bg-red-600 text-white px-3 py-2 rounded-lg">
                PDF
            </a>


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
                    <th class="px-6 py-3 text-left">Year</th>

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
                        {{ $student->study_year }}
                    </td>


                    <td class="px-6 py-4">
                        {{ $student->phone }}
                    </td>

                    <td class="px-6 py-4 text-right">
    <div class="flex justify-end gap-4">

        <!-- View / Info -->
        <a href="{{ route('admin.students.show', $student->id) }}"
           class="text-emerald-600 hover:text-emerald-800 transition hover:scale-110">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12
                         18 19.5 12 19.5 2.25 12 2.25 12z"/>
                <circle cx="12" cy="12" r="3.5"/>
            </svg>
        </a>

        <!-- Edit -->
        <a href="{{ route('admin.students.edit', $student->id) }}"
           class="text-indigo-600 hover:text-indigo-800 transition hover:scale-110">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.862 3.487a2.1 2.1 0 013.651 1.487
                         L7.5 18.987 3 21l2.013-4.5L16.862 3.487z"/>
            </svg>
        </a>

        <!-- Delete -->
        <button onclick="deleteStudent({{ $student->id }})"
           class="text-red-600 hover:text-red-800 transition hover:scale-110">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13M10 11v6M14 11v6"/>
            </svg>
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

            <div class="px-6 py-4 border-t">
                {{ $students->links() }}
            </div>
            <div class="text-sm text-gray-500 px-6 py-2">
                <!-- Total: {{ $students->total() }}, -->
                Page: {{ $students->currentPage() }}
            </div>

            </div>
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
