@extends('layouts.admin')

@section('title','Students')

@section('content')

<div class="bg-white rounded-2xl shadow border overflow-hidden">

    <!-- Top Bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 border-b bg-gray-50">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">Students</h2>
            <p class="text-sm text-gray-500">Manage all student records</p>
        </div>

        <!-- Search -->
        <form method="GET"
              action="{{ route('admin.students.index') }}"
              class="flex w-full md:w-96 gap-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search name, email or roll no..."
                class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-indigo-200 focus:outline-none">

            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 rounded-lg transition">
                Search
            </button>
        </form>

        <!-- Actions -->
        <div class="flex flex-wrap gap-2">

            <a href="{{ route('admin.students.export.excel') }}"
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                Excel
            </a>

            <a href="{{ route('admin.students.export.csv') }}"
               class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">
                CSV
            </a>

            <a href="{{ route('admin.students.export.pdf') }}"
               class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
                PDF
            </a>

            <a href="{{ route('admin.students.import.form') }}"
               class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">
                Upload
            </a>

            <a href="{{ route('admin.students.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                + Add Student
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Roll No</th>
                    <th class="px-6 py-3 text-left">Student</th>
                    <th class="px-6 py-3 text-left">Department</th>
                    <th class="px-6 py-3 text-left">Section</th>
                    <th class="px-6 py-3 text-left">Year</th>
                    <th class="px-6 py-3 text-left">Phone</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($students as $student)
                <tr class="hover:bg-indigo-50 transition">

                    <td class="px-6 py-4 font-semibold text-gray-700">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 rounded-md font-medium">
                            {{ $student->rollnum }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-800">{{ $student->name }}</div>
                        <div class="text-xs text-gray-500">{{ $student->email }}</div>
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->department->code ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->section->name ?? '-' }}
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
                    <td colspan="7" class="py-10 text-center text-gray-500">
                        No students found
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 border-t bg-gray-50">

        <div class="text-sm text-gray-500">
            Showing page {{ $students->currentPage() }} of {{ $students->lastPage() }}
        </div>

        <div>
            {{ $students->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
function deleteStudent(id) {

    Swal.fire({
        title: 'Delete this student?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Delete'
    }).then(result => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: `/admin/students/${id}`,
            type: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},

            success() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Deleted successfully',
                    showConfirmButton: false,
                    timer: 2000
                });

                location.reload();
            }
        });
    });
}
</script>
@endpush

@endsection
