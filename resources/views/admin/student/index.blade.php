@extends('layouts.admin')

@section('title','Students')

@section('content')

<div class="bg-white rounded-2xl shadow border overflow-hidden">

    <!-- Top Bar -->
    <!-- Top Bar -->
    <div class="bg-gray-50 border-b p-6 space-y-6 rounded-2xl">

        <!-- ROW 1 -->
        <div class="grid grid-cols-12 gap-2 items-center">

            <!-- Title -->
            <div class="col-span-12 lg:col-span-2 space-y-1">
                <h2 class="text-xl font-semibold text-gray-800">Students</h2>
                <p class="text-sm text-gray-500">Manage all student records</p>
            </div>

            <!-- Search -->
            <div class="col-span-12 lg:col-span-4">
                <form method="GET"
                    action="{{ route('admin.students.index') }}"
                    class="flex gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search name, email or roll no..."
                        class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-indigo-200">

                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 rounded-lg transition">
                        Search
                    </button>
                </form>
            </div>

            <!-- Actions -->
            <div class="col-span-12 lg:col-span-6 flex flex-wrap gap-2 justify-start lg:justify-end">
<a href="{{ route('admin.students.export.excel', request()->query()) }}"
   class="px-4 py-2 bg-emerald-600 text-white rounded-lg">
    Excel
</a>

<a href="{{ route('admin.students.export.csv', request()->query()) }}"
   class="px-4 py-2 bg-sky-600 text-white rounded-lg">
    CSV
</a>

<a href="{{ route('admin.students.export.pdf', request()->query()) }}"
   class="px-4 py-2 bg-rose-600 text-white rounded-lg">
    PDF
</a>


                <a href="{{ route('admin.students.import.form') }}"
                    class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">
                    Upload
                </a>

                <a href="{{ route('admin.students.create') }}"
                    class="px-4 py-2 bg-indigo-700 text-white rounded-lg font-medium hover:bg-indigo-800 transition">
                    + Add Student
                </a>
            </div>

        </div>



        <!-- ROW 2 : FILTER CARD -->
        <div class="flex justify-center">

            <div class="bg-white rounded-2xl shadow-sm border px-6 py-4 w-full max-w-4xl">

                <form method="GET"
                    action="{{ route('admin.students.index') }}"
                    class="flex flex-wrap gap-3 justify-center items-center">

                    <!-- Department -->
                    <select name="department"
                        class="rounded-lg border px-4 py-2 min-w-[160px] focus:ring-2 focus:ring-indigo-200">

                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ request('department') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->code }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Section -->
                    <select name="section" id="filterSection"
                        class="rounded-lg border px-4 py-2 min-w-[140px] focus:ring-2 focus:ring-indigo-200">
                        <option value="">All Sections</option>
                    </select>

                    <select name="year"
                        class="rounded-lg border px-4 py-2 min-w-[120px] focus:ring-2 focus:ring-indigo-200">

                        <option value="">All Years</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>


                    <!-- Year -->
                    <!-- <select name="year"
                class="rounded-lg border px-4 py-2 min-w-[120px] focus:ring-2 focus:ring-indigo-200">

                <option value="">All Years</option>
                @for($y = date('Y'); $y >= 2015; $y--)
                    <option value="{{ $y }}"
                        {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select> -->

                    <!-- Buttons -->
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition shadow-sm">
                Filter
            </button>

                    <a href="{{ route('admin.students.index') }}"
                        class="px-6 py-2 border rounded-lg hover:bg-gray-100 transition">
                        Reset
                    </a>

                </form>

            </div>

        </div>


    </div>



    <!-- Table -->

    <div id="studentsTable">
        @include('admin.student.partials.table', ['students' => $students])
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
                data: {
                    _token: '{{ csrf_token() }}'
                },

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
    $('select[name="department"]').on('change', function() {

        let deptId = $(this).val();
        let sectionBox = $('#filterSection');

        sectionBox.html('<option value="">Loading...</option>');

        if (!deptId) {
            sectionBox.html('<option value="">All Sections</option>');
            liveFilter();
            return;
        }

        $.get('/admin/departments/' + deptId + '/sections', function(data) {

            sectionBox.empty();
            sectionBox.append('<option value="">All Sections</option>');

            data.forEach(sec => {
                sectionBox.append(
                    `<option value="${sec.id}">${sec.name}</option>`
                );
            });

            liveFilter();
        });
    });

    // section change also filter
    $('#filterSection').on('change', liveFilter);


    function liveFilter() {
        $.ajax({
            url: "{{ route('admin.students.index') }}",
            data: {
                department: $('[name="department"]').val(),
                section: $('[name="section"]').val(),
                year: $('[name="year"]').val()
            },
            success: function(data) {
                $('#studentsTable').html(data);
            }
        });
    }

    // trigger on change
    $('select').on('change', function() {
        liveFilter();
    });
</script>

@endpush

@endsection