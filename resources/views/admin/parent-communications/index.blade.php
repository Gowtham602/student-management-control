@extends('layouts.admin')

@section('title', 'Parent Meeting Template')

@section('content')

<div class="p-6">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Parent Meeting Template
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Send communication messages to student parents.
        </p>
    </div>


    {{-- Main Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <div class="px-6 py-4 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-800">
                Create Template
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Select students and prepare the message.
            </p>

        </div>


        <div class="p-6">

            {{-- ============================= --}}
            {{-- Student Filter                 --}}
            {{-- ============================= --}}

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Department --}}
                <div>

                    <label
                        for="department_id"
                        class="block text-sm font-medium text-gray-700 mb-2">

                        Department

                    </label>

                    <select
                        id="department_id"
                        class="w-full rounded-lg border-gray-300
                               focus:border-indigo-500
                               focus:ring-indigo-500">

                        <option value="">
                            Select Department
                        </option>

                        @foreach($departments as $department)

                            <option value="{{ $department->id }}">
                                {{ $department->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Year --}}
                <div>

                    <label
                        for="year"
                        class="block text-sm font-medium text-gray-700 mb-2">

                        Year

                    </label>

                    <select
                        id="year"
                        class="w-full rounded-lg border-gray-300
                               focus:border-indigo-500
                               focus:ring-indigo-500">

                        <option value="">
                            Select Year
                        </option>

                        <option value="1">
                            I Year
                        </option>

                        <option value="2">
                            II Year
                        </option>

                        <option value="3">
                            III Year
                        </option>

                        <option value="4">
                            IV Year
                        </option>

                    </select>

                </div>


                {{-- Section --}}
                <div>

                    <label
                        for="section_id"
                        class="block text-sm font-medium text-gray-700 mb-2">

                        Section

                    </label>

                    <select
                        id="section_id"
                        class="w-full rounded-lg border-gray-300
                               focus:border-indigo-500
                               focus:ring-indigo-500">

                        <option value="">
                            Select Section
                        </option>

                        @foreach($sections as $section)

                            <option value="{{ $section->id }}">
                                {{ $section->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- Load Students Button --}}
            <div class="mt-5">

                <button
                    type="button"
                    id="loadStudents"
                    class="px-5 py-2.5 rounded-lg
                           bg-indigo-600 text-white
                           text-sm font-medium
                           hover:bg-indigo-700
                           transition">

                    Load Students

                </button>

            </div>


            {{-- ============================= --}}
            {{-- Students                      --}}
            {{-- ============================= --}}

            <div
                id="studentSection"
                class="hidden mt-6
                       border border-gray-200
                       rounded-xl">

                <div
                    class="px-5 py-4
                           border-b border-gray-200
                           flex items-center justify-between">

                    <div>

                        <h3 class="font-semibold text-gray-800">
                            Students
                        </h3>

                        <p
                            id="studentCount"
                            class="text-sm text-gray-500 mt-1">

                            0 students

                        </p>

                    </div>


                    <label
                        class="flex items-center gap-2
                               text-sm font-medium
                               text-gray-700">

                        <input
                            type="checkbox"
                            id="selectAll"
                            class="rounded border-gray-300
                                   text-indigo-600
                                   focus:ring-indigo-500">

                        Select All

                    </label>

                </div>


                <div
                    id="studentList"
                    class="p-5 max-h-80 overflow-y-auto">

                </div>

            </div>


            {{-- ============================= --}}
            {{-- Message                       --}}
            {{-- ============================= --}}

            <div
                id="messageSection"
                class="hidden mt-6">

                <div class="border-t border-gray-200 pt-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Message
                    </h3>


                    {{-- Template --}}
                    <div class="mb-5">

                        <label
                            for="template_id"
                            class="block text-sm font-medium
                                   text-gray-700 mb-2">

                            Message Template

                        </label>

                        <select
                            id="template_id"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-indigo-500
                                   focus:ring-indigo-500">

                            <option value="">
                                Select Message Template
                            </option>

                            @foreach($templates as $template)

                                <option
                                    value="{{ $template->id }}"
                                    data-message="{{ $template->message }}">

                                    {{ $template->name }}
                                    -
                                    {{ strtoupper($template->language) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Message --}}
                    <div>

                        <label
                            for="message"
                            class="block text-sm font-medium
                                   text-gray-700 mb-2">

                            Message

                        </label>

                        <textarea
                            id="message"
                            rows="5"
                            maxlength="1000"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-indigo-500
                                   focus:ring-indigo-500"
                            placeholder="Select a template..."></textarea>


                        <div class="flex justify-between mt-2">

                            <span class="text-xs text-gray-500">
                                Review the message before submitting.
                            </span>

                            <span
                                id="characterCount"
                                class="text-xs text-gray-500">

                                0 characters

                            </span>

                        </div>

                    </div>


                    {{-- Preview --}}
                    <div class="mt-5">

                        <h4 class="text-sm font-semibold text-gray-700 mb-2">
                            Message Preview
                        </h4>

                        <div
                            id="messagePreview"
                            class="bg-gray-50 border border-gray-200
                                   rounded-lg p-4 text-sm text-gray-700
                                   min-h-[100px]">

                            Your message preview will appear here.

                        </div>

                    </div>


                    {{-- Submit --}}
                    <div class="mt-6 flex justify-end">

                        <button
                            type="button"
                            id="submitCommunication"
                            class="px-6 py-2.5 rounded-lg
                                   bg-indigo-600 text-white
                                   text-sm font-medium
                                   hover:bg-indigo-700
                                   transition">

                            Submit for Approval

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>




@endsection
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    console.log('Parent Communication JS loaded');

    const loadStudentsButton =
        document.getElementById('loadStudents');

    const studentSection =
        document.getElementById('studentSection');

    const studentList =
        document.getElementById('studentList');

    const studentCount =
        document.getElementById('studentCount');

    const selectAll =
        document.getElementById('selectAll');

    const messageSection =
        document.getElementById('messageSection');

    const template =
        document.getElementById('template_id');

    const message =
        document.getElementById('message');

    const messagePreview =
        document.getElementById('messagePreview');

    const characterCount =
        document.getElementById('characterCount');

    const submitCommunication =
    document.getElementById('submitCommunication');


    console.log('Load button:', loadStudentsButton);


    /*
    |--------------------------------------------------------------------------
    | Load Students
    |--------------------------------------------------------------------------
    */

    loadStudentsButton.addEventListener('click', function () {

        console.log('Load Students clicked');


        const departmentId =
            document.getElementById('department_id').value;

        const year =
            document.getElementById('year').value;

        const sectionId =
            document.getElementById('section_id').value;


        console.log({
            departmentId,
            year,
            sectionId
        });


        if (!departmentId || !year || !sectionId) {

            alert(
                'Please select Department, Year and Section.'
            );

            return;
        }


        loadStudentsButton.disabled = true;

        loadStudentsButton.innerText = 'Loading...';


        const url =
            "{{ route('admin.parent-communications.students') }}" +
            '?' +
            new URLSearchParams({
                department_id: departmentId,
                year: year,
                section_id: sectionId
            });


        console.log('Students URL:', url);


        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })

        .then(async response => {

            console.log('HTTP status:', response.status);

            const data = await response.json();

            console.log('Response:', data);

            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'Unable to load students.'
                );
            }

            return data;

        })

        .then(data => {

            studentList.innerHTML = '';

            selectAll.checked = false;


            if (
                !data.success ||
                !data.students ||
                data.students.length === 0
            ) {

                studentSection.classList.remove('hidden');

                messageSection.classList.add('hidden');

                studentCount.innerText =
                    '0 students';

                studentList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        No students found.
                    </div>
                `;

                return;
            }


            data.students.forEach(student => {

                studentList.innerHTML += `

                    <label
                        class="flex items-center gap-3
                               p-3 rounded-lg
                               hover:bg-gray-50
                               border-b border-gray-100">

                        <input
                            type="checkbox"
                            class="student-checkbox
                                   rounded border-gray-300
                                   text-indigo-600
                                   focus:ring-indigo-500"
                            value="${student.id}">

                        <div>

                            <div class="text-sm font-medium text-gray-800">

                                ${student.name}

                            </div>

                            <div class="text-xs text-gray-500">

                                Roll No:
                                ${student.rollnum ?? '-'}

                            </div>

                        </div>

                    </label>

                `;

            });


            studentCount.innerText =
                `${data.students.length} students`;


            studentSection.classList.remove('hidden');

            messageSection.classList.remove('hidden');

        })

        .catch(error => {

            console.error(
                'Load Students Error:',
                error
            );

            alert(error.message);

        })

        .finally(() => {

            loadStudentsButton.disabled = false;

            loadStudentsButton.innerText =
                'Load Students';

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    selectAll.addEventListener('change', function () {

        document
            .querySelectorAll('.student-checkbox')
            .forEach(checkbox => {

                checkbox.checked =
                    selectAll.checked;

            });

    });


    /*
    |--------------------------------------------------------------------------
    | Template
    |--------------------------------------------------------------------------
    */

    template.addEventListener('change', function () {

        const selectedOption =
            this.options[this.selectedIndex];

        const templateMessage =
            selectedOption.dataset.message || '';

        message.value =
            templateMessage;

        updatePreview();

    });


    /*
    |--------------------------------------------------------------------------
    | Message
    |--------------------------------------------------------------------------
    */

    message.addEventListener('input', function () {

        updatePreview();

    });


    function updatePreview()
    {

        messagePreview.textContent =
            message.value ||
            'Your message preview will appear here.';


        characterCount.innerText =
            `${message.value.length} characters`;

    }

    /*
|--------------------------------------------------------------------------
| Submit Communication
|--------------------------------------------------------------------------
*/

submitCommunication.addEventListener('click', function () {

    const departmentId =
        document.getElementById('department_id').value;

    const year =
        document.getElementById('year').value;

    const sectionId =
        document.getElementById('section_id').value;

    const templateId =
        document.getElementById('template_id').value;

    const messageValue =
        document.getElementById('message').value.trim();


    const studentIds = Array.from(
        document.querySelectorAll('.student-checkbox:checked')
    ).map(checkbox => checkbox.value);


    console.log('SUBMIT DATA:', {
        departmentId,
        year,
        sectionId,
        templateId,
        message: messageValue,
        studentIds
    });


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (!departmentId) {

      toastr.warning('Please select Department.');

        return;
    }


    if (!year) {

     toastr.warning('Please select Year.');

        return;
    }


    if (!sectionId) {

        toastr.warning('Please select Section.');

        return;
    }


    if (studentIds.length === 0) {

     toastr.warning('Please select at least one student.');

        return;
    }


    if (!templateId) {

      toastr.warning('Please select a message template.');

        return;
    }


    if (!messageValue) {
toastr.warning('Please enter the message.');

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Disable button
    |--------------------------------------------------------------------------
    */

    submitCommunication.disabled = true;

    submitCommunication.innerText =
        'Submitting...';


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    fetch(
        "{{ route('admin.parent-communications.store') }}",
        {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',

                'Accept': 'application/json',

                'X-Requested-With': 'XMLHttpRequest',

                'X-CSRF-TOKEN':
                    document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
            },

            body: JSON.stringify({

                department_id: departmentId,

                year: year,

                section_id: sectionId,

                template_id: templateId,

                message: messageValue,

                student_ids: studentIds

            })
        }
    )

    .then(async response => {

        const data = await response.json();

        console.log('SUBMIT RESPONSE:', data);

        if (!response.ok) {

            throw new Error(
                data.message ||
                'Unable to submit communication.'
            );
        }

        return data;

    })

    .then(data => {

        if (data.success) {

             toastr.success(
        'Communication submitted successfully and is now PENDING approval.'
    );

    setTimeout(() => {
        window.location.reload();
    }, 1500);

            window.location.reload();

        } else {

          toastr.error(
    data.message ||
    'Unable to submit communication.'
);

        }

    })

    .catch(error => {

        console.error(
            'SUBMIT ERROR:',
            error
        );

        toastr.error(
        error.message || 'Something went wrong.'
    );

    })

    .finally(() => {

        submitCommunication.disabled = false;

        submitCommunication.innerText =
            'Submit for Approval';

    });

});

});

</script>

@endpush