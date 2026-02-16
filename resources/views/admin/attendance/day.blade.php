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

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow border overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">S.No</th>
                    <th class="px-4 py-3">Roll No</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Year</th>
                    <th class="px-4 py-3">Department</th>
                    <th class="px-4 py-3">Section</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @if(request()->filled(['department','section','year']))

                    @forelse($students as $student)

                        @php
                            $attendance = $student->attendances->first();
                            $status = $attendance->status ?? '-';
                            $year = now()->year - $student->admission_year + 1;
                        @endphp

                        <tr class="hover:bg-indigo-50">
                            <td class="px-4 py-3">
                                {{ $students->firstItem() + $loop->index }}
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $student->rollnum }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $student->name }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $year }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $student->department->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $student->section->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $status === 'P' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $status === 'A' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $status === 'H' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $status === '-' ? 'bg-gray-100 text-gray-500' : '' }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-400">
                                No students found.
                            </td>
                        </tr>
                    @endforelse

                @else
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-400">
                            Please select Department, Year and Section.
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    @if(request()->filled(['department','section','year']))
        <div class="mt-4">
            {{ $students->links() }}
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
