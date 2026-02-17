@extends('layouts.admin')
@section('title','Student Day Attendance')

@section('content')

<div class="space-y-6">

    <!-- FILTER FORM -->
    <form method="GET" class="flex flex-wrap gap-3 mb-5">

        <!-- Date -->
        <input type="date" name="date" value="{{ $date }}"
            class="border rounded-lg px-3 py-2 text-sm">

        <!-- Department -->
        <select name="department" id="department"
            class="border rounded-lg px-3 py-2 text-sm">
            <option value="">Select Department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}"
                    {{ request('department') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>

        <!-- Year -->
        <select name="year" id="year"
            class="border rounded-lg px-3 py-2 text-sm">
            <option value="">Select Year</option>
            <option value="1" {{ request('year') == 1 ? 'selected' : '' }}>1st Year</option>
            <option value="2" {{ request('year') == 2 ? 'selected' : '' }}>2nd Year</option>
            <option value="3" {{ request('year') == 3 ? 'selected' : '' }}>3rd Year</option>
            <option value="4" {{ request('year') == 4 ? 'selected' : '' }}>4th Year</option>
        </select>

        <!-- Section -->
        <select name="section" id="section"
            class="border rounded-lg px-3 py-2 text-sm">
            <option value="">Select Section</option>
            @foreach($sections as $sec)
                <option value="{{ $sec->id }}"
                    {{ request('section') == $sec->id ? 'selected' : '' }}>
                    {{ $sec->name }}
                </option>
            @endforeach
        </select>

        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
            Filter
        </button>

        <a href="{{ route('admin.attendance.day') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Reset
        </a>

    </form>
    @if($students->count())

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-indigo-100 p-4 rounded-xl shadow">
        <h3 class="text-sm text-gray-600">Total Students</h3>
        <p class="text-2xl font-bold text-indigo-700">
            {{ $students->count() }}
        </p>
    </div>

    <div class="bg-green-100 p-4 rounded-xl shadow">
        <h3 class="text-sm text-gray-600">Present</h3>
        <p class="text-2xl font-bold text-green-700">
            {{ $presentCount }}
        </p>
    </div>

    <div class="bg-red-100 p-4 rounded-xl shadow">
        <h3 class="text-sm text-gray-600">Absent</h3>
        <p class="text-2xl font-bold text-red-700">
            {{ $absentCount }}
        </p>
    </div>

    <div class="bg-yellow-100 p-4 rounded-xl shadow">
        <h3 class="text-sm text-gray-600">Not Marked</h3>
        <p class="text-2xl font-bold text-yellow-700">
            {{ $notMarked }}
        </p>
    </div>

</div>

@endif


    <!-- TABLE -->
   <!-- TABLE -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">

            <!-- HEADER -->
            <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 text-gray-700 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-5 py-4 text-left w-16">#</th>
                    <th class="px-5 py-4 text-left">Roll No</th>
                    <th class="px-5 py-4 text-left">Student Name</th>
                    <th class="px-5 py-4 text-left">Year</th>
                    <th class="px-5 py-4 text-left">Department</th>
                    <th class="px-5 py-4 text-left">Section</th>
                    <th class="px-5 py-4 text-center">Status</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody class="divide-y divide-gray-100 bg-white">

                @if(request()->filled(['department','section','year']))

                    @forelse($students as $student)

                        @php
                            $attendance = $student->attendances->first();
                            $status = $attendance->status ?? '-';
                            $year = now()->year - $student->admission_year + 1;
                        @endphp

                        <tr class="hover:bg-indigo-50 transition duration-150">

                            <!-- S.NO -->
                            <td class="px-5 py-4 font-medium text-gray-500">
                                {{ $loop->iteration }}
                            </td>

                            <!-- ROLL -->
                            <td class="px-5 py-4 font-semibold text-indigo-600">
                                {{ $student->rollnum }}
                            </td>

                            <!-- NAME -->
                            <td class="px-5 py-4">
                                <div class="font-medium text-gray-800">
                                    {{ $student->name }}
                                </div>
                            </td>

                            <!-- YEAR -->
                            <td class="px-5 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                                    {{ $year }} Year
                                </span>
                            </td>

                            <!-- DEPT -->
                            <td class="px-5 py-4">
                                {{ $student->department->name ?? '-' }}
                            </td>

                            <!-- SECTION -->
                            <td class="px-5 py-4">
                                {{ $student->section->name ?? '-' }}
                            </td>

                            <!-- STATUS -->
                           <td class="px-5 py-4 text-center">

    <form method="POST" action="{{ route('admin.attendance.update') }}">
        @csrf

        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <select name="status"
            onchange="this.form.submit()"
            class="px-3 py-1 text-xs rounded-full border
            {{ $status == 'P' ? 'bg-green-100 text-green-700'
            : ($status == 'A' ? 'bg-red-100 text-red-700'
            : 'bg-gray-100 text-gray-600') }}">

            <option value="">Not Marked</option>
            <option value="P" {{ $status=='P' ? 'selected' : '' }}>Present</option>
            <option value="A" {{ $status=='A' ? 'selected' : '' }}>Absent</option>
        </select>

    </form>

</td>


                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl"></span>
                                    <p>No students found for selected filters.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                @else
                    <tr>
                        <td colspan="7" class="text-center py-16 text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-4xl"></span>
                                <p>Please select Department, Year and Section.</p>
                            </div>
                        </td>
                    </tr>
                @endif

            </tbody>

        </table>
    </div>
</div>


    @if(request()->filled(['department','section','year']))
        <div class="mt-4">
            <!-- {{ $students->links() }} -->
        </div>
    @endif

</div>


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    let baseUrl = "{{ url('admin/departments') }}";
    let department = document.getElementById('department');
    let sectionDropdown = document.getElementById('section');

    department.addEventListener('change', function () {

        let departmentId = this.value;

        if (!departmentId) {
            sectionDropdown.innerHTML =
                '<option value="">Select Section</option>';
            return;
        }

        sectionDropdown.innerHTML =
            '<option value="">Loading...</option>';

        fetch(baseUrl + '/' + departmentId + '/sections')
            .then(response => response.json())
            .then(data => {

                sectionDropdown.innerHTML =
                    '<option value="">Select Section</option>';

                if (data.length === 0) {
                    sectionDropdown.innerHTML =
                        '<option value="">No Sections Found</option>';
                    return;
                }

                data.forEach(section => {
                    sectionDropdown.innerHTML +=
                        `<option value="${section.id}">
                            ${section.name}
                        </option>`;
                });

            })
            .catch(error => {
                console.error(error);
                sectionDropdown.innerHTML =
                    '<option value="">Error loading sections</option>';
            });

    });

});
</script>
@endpush

@endsection
