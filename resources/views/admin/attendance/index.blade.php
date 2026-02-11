@extends('layouts.admin')
@section('title','Bulk Attendance')

@section('content')

<div class="space-y-6">

    <!-- FILTER CARD -->
    <div class="bg-white rounded-2xl shadow border p-5">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Student Filter
        </h2>

        <!-- <form method="GET"  id="filterForm" class="grid grid-cols-1 md:grid-cols-6 gap-4"> -->
            <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-6 gap-4">


            <!-- <input type="date" name="date" value="{{ $date }}"
                   class="border rounded-lg px-3 py-2"> -->

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search name / roll"
                class="border rounded-lg px-3 py-2">

            <!-- <select name="department" class="border rounded-lg px-3 py-2">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" @selected(request('department')==$dept->id)>
                    {{ $dept->name }}
                </option>
                @endforeach
            </select> -->

            <select name="department" id="department"
                class="border rounded-lg px-3 py-2">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>


            <!-- <select name="section" class="border rounded-lg px-3 py-2">
                <option value="">All Sections</option>
                @foreach($sections as $sec)
                <option value="{{ $sec->id }}" @selected(request('section')==$sec->id)>
                    {{ $sec->name }}
                </option>
                @endforeach
            </select> -->
            <select name="section" id="section"
                    class="border rounded-lg px-3 py-2" disabled>
                <option value="">Select Department First</option>
            </select>



            <select name="year" class="border rounded-lg px-3 py-2">
                <option value="">All Years</option>
                @for($i=1;$i<=4;$i++)
                    <option value="{{ $i }}" @selected(request('year')==$i)>
                    Year {{ $i }}
                    </option>
                    @endfor
            </select>

            <!-- <button class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 font-medium">
                Filter
            </button> -->

        </form>
    </div>

    <!-- ATTENDANCE ACTION CARD -->
    <div class="bg-white rounded-2xl shadow border p-5">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Mark Attendance
        </h2>

        <form id="attendanceForm"  method="POST" action="{{ route('admin.attendance.bulkSave') }}">
            @csrf

            <!-- ACTION BAR -->
            <div class="flex flex-wrap items-center gap-4 mb-5 bg-gray-50 p-4 rounded-xl border">

                <div>
                    <label class="text-xs text-gray-500 block">Attendance Date</label>
                    <input type="date" name="date" value="{{ $date }}"
                        class="border rounded-lg px-3 py-2 font-semibold" required>
                </div>

                <div>
                    <label class="text-xs text-gray-500 block">Status</label>
                    <select name="status"
                        class="border rounded-lg px-4 py-2 font-semibold">
                        <option value="P"> Present</option>
                        <option value="A">Absent</option>
                        <option value="H"> Holiday</option>
                    </select>
                </div>

                <div class="mt-4 md:mt-0">
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-semibold shadow">
                        Save Attendance
                    </button>
                </div>

            </div>
            <!-- SELECTED STUDENTS PREVIEW -->
<div class="mb-4">
    <h3 class="text-sm font-semibold text-gray-700 mb-2">
        Selected Students:
        <span id="selectedCount" class="text-indigo-600">0</span>
    </h3>

    <div id="selectedPreview"
         class="flex flex-wrap gap-2 bg-gray-50 p-3 rounded-lg border min-h-[40px]">
    </div>
</div>


            <!-- STUDENT TABLE -->
            <div class="overflow-x-auto rounded-xl border">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-center">
                                <input type="checkbox" id="checkAll"
                                    class="w-4 h-4 rounded border-gray-300">
                            </th>
                            <th class="px-4 py-3">Roll No</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Year</th>
                            <th class="px-4 py-3">Department</th>
                            <th class="px-4 py-3">Section</th>
                        </tr>
                    </thead>

                    <tbody id="studentsTable" class="divide-y">
                        @foreach($students as $student)
                        @php
                        $yearLevel = now()->year - $student->admission_year + 1;
                        $yearLabel = $yearLevel >= 1 && $yearLevel <= 4
                            ? $yearLevel . ' Year'
                            : 'Passout' ;
                            @endphp

                            <tr class="hover:bg-indigo-50 transition">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox"
                                    name="students[]"
                                    value="{{ $student->id }}"
                                    class="student-check w-4 h-4 rounded border-gray-300">
                            </td>

                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $student->rollnum }}
                            </td>

                            <td class="px-4 py-3 text-gray-900">
                                {{ $student->name }}
                            </td>

                            <!-- YEAR BADGE -->
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold
                        {{ $yearLabel === 'Passout'
                            ? 'bg-gray-200 text-gray-700'
                            : 'bg-indigo-100 text-indigo-700' }}">
                                    <!-- Graduation Cap SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 14v7" />
                                    </svg>
                                    {{ $yearLabel }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $student->department->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $student->section->name ?? '-' }}
                            </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>


        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>

let selectedStudents = new Set();
console.log(selectedStudents,"_____new select student");

const search     = document.querySelector('input[name="search"]');
const department = document.getElementById('department');
const section    = document.getElementById('section');
const year       = document.querySelector('select[name="year"]');

let timer;

//  Load students via AJAX
// function loadStudents() {
//     const params = new URLSearchParams({
//         search: search.value,
//         department: department.value,
//         section: section.value,
//         year: year.value,
//     });

//     fetch(`{{ route('admin.attendance.ajaxStudents') }}?${params}`)
//         .then(res => res.text())
//         .then(html => {
//             document.getElementById('studentsTable').innerHTML = html;
//         });
//                 //  Re-check selected students
//             document.querySelectorAll('.student-check').forEach(cb => {
//                 if (selectedStudents.has(cb.value)) {
//                     cb.checked = true;
//                 }
//             });



// }
function loadStudents() {
    const params = new URLSearchParams({
        search: search.value,
        department: department.value,
        section: section.value,
        year: year.value,
    });

    fetch(`{{ route('admin.attendance.ajaxStudents') }}?${params}`)
        .then(res => res.text())
        .then(html => {

            document.getElementById('studentsTable').innerHTML = html;

            //  Re-check selected students AFTER table reload
            document.querySelectorAll('.student-check').forEach(cb => {
                if (selectedStudents.has(cb.value)) {
                    cb.checked = true;
                }
            });

        });
}

document.addEventListener('DOMContentLoaded', function () {

    const attendanceForm = document.getElementById('attendanceForm');

    attendanceForm.addEventListener('submit', function () {

        // Remove old hidden inputs
       let sample= document.querySelectorAll('.hidden-student').forEach(e => e.remove());
    console.log(sample,"_____sample");
        selectedStudents.forEach(id => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'students[]';
            input.value = id;
            input.classList.add('hidden-student');
            this.appendChild(input);
        });

    });

});

function updateSelectedPreview() {
    const preview = document.getElementById('selectedPreview');
    const count   = document.getElementById('selectedCount');

    preview.innerHTML = '';

    selectedStudents.forEach(id => {

        const badge = document.createElement('div');
        badge.className =
            "px-3 py-1 bg-indigo-100 text-indigo-700 text-xs rounded-full flex items-center gap-2";

        badge.innerHTML = `
            ID: ${id}
            <button type="button"
                class="remove-btn text-red-500 font-bold"
                data-id="${id}">
                ×
            </button>
        `;

        preview.appendChild(badge);
    });

    count.innerText = selectedStudents.size;
}


//  Auto search
search.addEventListener('keyup', () => {
    clearTimeout(timer);
    timer = setTimeout(loadStudents, 400);
});

//  Filters
department.addEventListener('change', loadStudents);
section.addEventListener('change', loadStudents);
year.addEventListener('change', loadStudents);

//  Department → Section
department.addEventListener('change', function () {
    const deptId = this.value;

    section.innerHTML = '<option>Loading...</option>';
    section.disabled = true;

    if (!deptId) {
        section.innerHTML = '<option>Select Department First</option>';
        return;
    }

    fetch(`/admin/departments/${deptId}/sections`)
        .then(res => res.json())
        .then(data => {
            section.innerHTML = '<option value="">All Sections</option>';
            data.forEach(sec => {
                section.innerHTML +=
                    `<option value="${sec.id}">${sec.name}</option>`;
            });
            section.disabled = false;
        });
});

//  Check all
// document.getElementById('checkAll').addEventListener('change', function () {
//     document.querySelectorAll('.student-check')
//         .forEach(cb => cb.checked = this.checked);
// });
document.getElementById('checkAll').addEventListener('change', function () {

    document.querySelectorAll('.student-check').forEach(cb => {

        cb.checked = this.checked;

        if (this.checked) {
            selectedStudents.add(cb.value);
        } else {
            selectedStudents.delete(cb.value);
        }

    });

    updateSelectedPreview();
});



// Handle checkbox click
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('student-check')) {
        const studentId = e.target.value;

        if (e.target.checked) {
            selectedStudents.add(studentId);
        } else {
            selectedStudents.delete(studentId);
        }
        updateSelectedPreview();
    }
});

</script>

@endpush