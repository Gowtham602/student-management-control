
@extends('layouts.admin')
@section('title', 'Bulk Attendance')

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



                <!-- <select name="department" class="border rounded-lg px-3 py-2">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(request('department')==$dept->id)>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </select> -->


                <select name="department" id="department" class="border rounded-lg px-3 py-2">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                <select name="year" class="border rounded-lg px-3 py-2">
                    <option value="">All Years</option>
                    @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" @selected(request('year') == $i)>
                            Year {{ $i }}
                        </option>
                    @endfor
                </select>


                <!-- <select name="section" class="border rounded-lg px-3 py-2">
                        <option value="">All Sections</option>
                        @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" @selected(request('section')==$sec->id)>
                            {{ $sec->name }}
                        </option>
                        @endforeach
                    </select> -->
                <select name="section" id="section" class="border rounded-lg px-3 py-2" disabled>
                    <option value="">Select Department First</option>
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name / roll"
                    class="border rounded-lg px-3 py-2">





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

            <form id="attendanceForm" method="POST" action="{{ route('admin.attendance.bulkSave') }}">
                @csrf

                <!-- ACTION BAR -->
                <div class="flex flex-wrap items-center gap-4 mb-5 bg-gray-50 p-4 rounded-xl border">

                    <input type="hidden" name="department" id="hiddenDepartment">
                    <input type="hidden" name="section" id="hiddenSection">
                    <input type="hidden" name="year" id="hiddenYear">

                    <div>
                        <label class="text-xs text-gray-500 block">
                            Attendance Date
                        </label>

                        <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                            max="{{ now()->format('Y-m-d') }}" class="border rounded-lg px-3 py-2 font-semibold" required>
                    </div>


                    @if(!$attendanceExists)

                        <div class="mt-4 md:mt-0">
                            <button type="submit" id="saveAttendanceBtn"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-semibold shadow">
                                Save Attendance
                            </button>
                        </div>

                    @else

                        <div class="mt-4 md:mt-0">
                            <button type="button" disabled
                                class="bg-gray-400 text-white px-6 py-2 rounded-lg font-semibold cursor-not-allowed">
                                Attendance Locked
                            </button>
                        </div>

                    @endif

                </div>
                @if($attendanceExists)

                    <div class="text-center py-10 bg-red-50 border rounded-xl mt-4">
                        <h3 class="text-lg font-semibold text-red-600">
                            Attendance is Locked for this Date
                        </h3>
                        <p class="text-gray-600 mt-2">
                            Present and Absent already marked.
                        </p>
                    </div>

                @else

                    <!-- SELECTED STUDENTS PREVIEW -->
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">
                            Selected Students:
                            <span id="selectedCount" class="text-indigo-600">0</span>
                        </h3>

                        <div id="selectedPreview" class="flex flex-wrap gap-2 bg-gray-50 p-3 rounded-lg border min-h-[40px]">
                        </div>
                    </div>

                    <!-- STUDENT TABLE -->
                    <div class="overflow-x-auto rounded-xl border">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-center">
                                        <input type="checkbox" id="checkAll" class="w-4 h-4 rounded border-gray-300">
                                    </th>
                                    <th class="px-4 py-3">Roll No</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Year</th>
                                    <th class="px-4 py-3">Department</th>
                                    <th class="px-4 py-3">Section</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>

                            <tbody id="studentsTable" class="divide-y">

                                @forelse($students as $student)

                                    @php
                                        $yearLabel = $student->study_year;
                                    @endphp

                                    <tr class="hover:bg-indigo-50 transition">

                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" value="{{ $student->id }}" data-name="{{ $student->name }}"
                                                data-department="{{ $student->department->name ?? '-' }}"
                                                data-year="{{ $yearLabel }}" class="student-check w-4 h-4 rounded border-gray-300">
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $student->rollnum }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $student->name }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $yearLabel }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $student->department->name ?? '-' }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $student->section->name ?? '-' }}
                                        </td>

                                        <td class="px-4 py-3">
                                            Not Marked
                                        </td>

                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-gray-500">
                                            No students found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                @endif



            </form>
        </div>

    </div>

@endsection

@push('scripts')

    <script>

        let selectedStudents = {};

        const search = document.querySelector('input[name="search"]');
        const department = document.getElementById('department');
        const section = document.getElementById('section');
        const year = document.querySelector('select[name="year"]');

        const attendanceForm = document.getElementById('attendanceForm');

        let timer;


        /*
        |--------------------------------------------------------------------------
        | LOAD STUDENTS
        |--------------------------------------------------------------------------
        */

        function loadStudents() {

            if (!department.value || !section.value || !year.value) {
                return;
            }

            const params = new URLSearchParams({
                search: search.value,
                department: department.value,
                section: section.value,
                year: year.value,
                date: document.querySelector('input[name="date"]').value
            });

            fetch(`{{ route('admin.attendance.ajaxStudents') }}?${params}`)
                .then(res => res.json())
                .then(data => {

                    // Load students/table
                    document.getElementById('studentsTable').innerHTML = data.html;

                    // Get Save button
                    const saveButton =
                        document.getElementById('saveAttendanceBtn');

                    // ==============================
                    // ATTENDANCE ALREADY EXISTS
                    // ==============================

                    if (data.attendanceExists === true) {

                        saveButton.disabled = true;

                        saveButton.type = 'button';

                        saveButton.innerText = 'Attendance Locked';

                        saveButton.classList.remove(
                            'bg-emerald-600',
                            'hover:bg-emerald-700'
                        );

                        saveButton.classList.add(
                            'bg-gray-400',
                            'cursor-not-allowed'
                        );

                    }

                    // ==============================
                    // ATTENDANCE NOT EXISTS
                    // ==============================

                    else {

                        saveButton.disabled = false;

                        saveButton.type = 'submit';

                        saveButton.innerText = 'Save Attendance';

                        saveButton.classList.remove(
                            'bg-gray-400',
                            'cursor-not-allowed'
                        );

                        saveButton.classList.add(
                            'bg-emerald-600',
                            'hover:bg-emerald-700'
                        );
                    }

                    // Keep selected students checked
                    document.querySelectorAll('.student-check').forEach(cb => {

                        if (selectedStudents[cb.value]) {
                            cb.checked = true;
                        }

                    });

                })
                .catch(error => {

                    console.error(
                        'Attendance AJAX Error:',
                        error
                    );

                });
        }
       /*
|--------------------------------------------------------------------------
| DEPARTMENT → SECTION attendance bulk attendance select department nd load section
|--------------------------------------------------------------------------
*/


department.addEventListener('change', function () {

    const deptId = this.value;

    section.innerHTML =
        '<option value="">Loading...</option>';

    section.disabled = true;

    if (!deptId) {

        section.innerHTML =
            '<option value="">Select Department First</option>';

        document.getElementById('studentsTable').innerHTML = '';

        return;
    }

    const url =
        "{{ route('admin.departments.sections', ['department' => '__ID__']) }}"
            .replace('__ID__', deptId);

    console.log('SECTION URL:', url);

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {

        console.log('SECTION STATUS:', res.status);

        if (!res.ok) {
            throw new Error('HTTP ' + res.status);
        }

        return res.json();
    })
    .then(data => {

        console.log('SECTIONS:', data);

        section.innerHTML =
            '<option value="">Select Section</option>';

        data.forEach(sec => {

            section.innerHTML +=
                `<option value="${sec.id}">
                    ${sec.name}
                </option>`;
        });

        section.disabled = false;
    })
    .catch(error => {

        console.error('SECTION ERROR:', error);

        section.innerHTML =
            '<option value="">Unable to load sections</option>';

        section.disabled = true;
    });

});


        /*
        |--------------------------------------------------------------------------
        | SECTION CHANGE
        |--------------------------------------------------------------------------
        */

        section.addEventListener('change', function () {

            loadStudents();

        });


        /*
        |--------------------------------------------------------------------------
        | YEAR CHANGE
        |--------------------------------------------------------------------------
        */

        year.addEventListener('change', function () {

            loadStudents();

        });


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        search.addEventListener('keyup', function () {

            clearTimeout(timer);

            timer = setTimeout(function () {

                loadStudents();

            }, 400);

        });


        /*
        |--------------------------------------------------------------------------
        | CHECK ALL
        |--------------------------------------------------------------------------
        */

        document.getElementById('checkAll').addEventListener('change', function () {

            const checked = this.checked;

            document.querySelectorAll('.student-check').forEach(cb => {

                cb.checked = checked;

                const id = cb.value;

                if (checked) {

                    selectedStudents[id] = {

                        name: cb.dataset.name,
                        department: cb.dataset.department,
                        year: cb.dataset.year

                    };

                } else {

                    delete selectedStudents[id];

                }

            });

            updateSelectedPreview();

        });


        /*
        |--------------------------------------------------------------------------
        | INDIVIDUAL STUDENT CHECKBOX
        |--------------------------------------------------------------------------
        */

        document.addEventListener('change', function (e) {

            if (!e.target.classList.contains('student-check')) {
                return;
            }

            const id = e.target.value;

            if (e.target.checked) {

                selectedStudents[id] = {

                    name: e.target.dataset.name,
                    department: e.target.dataset.department,
                    year: e.target.dataset.year

                };

            } else {

                delete selectedStudents[id];

            }

            updateSelectedPreview();

        });


        /*
        |--------------------------------------------------------------------------
        | PREVIEW
        |--------------------------------------------------------------------------
        */

        function updateSelectedPreview() {

            const preview = document.getElementById('selectedPreview');
            const count = document.getElementById('selectedCount');

            preview.innerHTML = '';

            Object.keys(selectedStudents).forEach(id => {

                const student = selectedStudents[id];

                const badge = document.createElement('div');

                badge.className =
                    'px-3 py-2 bg-indigo-100 text-indigo-800 text-xs rounded-xl';

                badge.innerHTML = `
                <b>${student.name}</b>
                <span class="text-gray-500">
                    ${student.department} • ${student.year}
                </span>
            `;

                preview.appendChild(badge);

            });

            count.innerText =
                Object.keys(selectedStudents).length;

        }


        /*
        |--------------------------------------------------------------------------
        | SAVE BULK ATTENDANCE
        |--------------------------------------------------------------------------
        */

        attendanceForm.addEventListener('submit', function (e) {

            e.preventDefault();


            if (!department.value) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Please select Department'
                });

                return;
            }


            if (!section.value) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Please select Section'
                });

                return;
            }


            if (!year.value) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Please select Year'
                });

                return;
            }


            const absentCount =
                Object.keys(selectedStudents).length;


            Swal.fire({

                title: 'Confirm Attendance',

                html: `
                <div style="text-align:left">
                    <p>
                        <b>Absent Students:</b>
                        ${absentCount}
                    </p>

                    <p>
                        <b>Present Students:</b>
                        Remaining students
                    </p>

                    <br>

                    <p>
                        Are you sure you want to mark
                        remaining students as
                        <b>Present</b>?
                    </p>
                </div>
            `,

                icon: 'question',

                showCancelButton: true,

                confirmButtonText: 'Yes, Confirm',

                cancelButtonText: 'Cancel'

            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }

                Swal.fire({
                    title: 'Saving Attendance...',
                    html: `
                <div style="text-align:center;">
                    <p>
                        Please wait while attendance is being processed.
                    </p>

                    <p style="margin-top:10px;">
                        📩 <b>Sending absence message to parents...</b>
                    </p>
                </div>
            `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                /*
                |--------------------------------------------------------------------------
                | FILTER VALUES
                |--------------------------------------------------------------------------
                */

                document.getElementById('hiddenDepartment').value =
                    department.value;

                document.getElementById('hiddenSection').value =
                    section.value;

                document.getElementById('hiddenYear').value =
                    year.value;


                /*
                |--------------------------------------------------------------------------
                | REMOVE OLD HIDDEN INPUTS
                |--------------------------------------------------------------------------
                */

                attendanceForm
                    .querySelectorAll('.hidden-student')
                    .forEach(el => el.remove());


                /*
                |--------------------------------------------------------------------------
                | ADD ABSENT STUDENTS
                |--------------------------------------------------------------------------
                */

                Object.keys(selectedStudents).forEach(id => {

                    const input =
                        document.createElement('input');

                    input.type = 'hidden';

                    input.name = 'students[]';

                    input.value = id;

                    input.classList.add('hidden-student');

                    attendanceForm.appendChild(input);

                });


                /*
                |--------------------------------------------------------------------------
                | SUBMIT
                |--------------------------------------------------------------------------
                */

                attendanceForm.submit();

            });

        });

    </script>
@endpush