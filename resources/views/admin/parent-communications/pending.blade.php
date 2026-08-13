@extends('layouts.admin')

@section('title', 'Pending Parent Communications')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Pending Parent Communications
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Review and approve parent communication messages before sending.
        </p>

    </div>


    {{-- Pending Communications --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <div class="px-6 py-4 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-800">
                Pending Communications
            </h2>

        </div>


        @if($communications->count())

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                #
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Department
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Section
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Template
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Students
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Submitted
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($communications as $communication)

                            <tr class="hover:bg-gray-50">

                                {{-- ID --}}
                                <td class="px-6 py-4 text-sm text-gray-700">

                                    #{{ $communication->id }}

                                </td>


                                {{-- Department --}}
                                <td class="px-6 py-4">

                                    <div class="text-sm font-medium text-gray-800">

                                        {{ $communication->department->name ?? '-' }}

                                    </div>

                                </td>


                                {{-- Section --}}
                                <td class="px-6 py-4">

                                    <span class="text-sm text-gray-700">

                                        {{ $communication->section->name ?? '-' }}

                                    </span>

                                </td>


                                {{-- Template --}}
                                <td class="px-6 py-4">

                                    <div class="text-sm font-medium text-gray-800">

                                        {{ $communication->template->name ?? '-' }}

                                    </div>

                                    @if($communication->template)

                                        <div class="text-xs text-gray-500 mt-1">

                                            {{ strtoupper($communication->template->language) }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Students --}}
                                <td class="px-6 py-4">

                                    <span
                                        class="inline-flex items-center
                                               px-2.5 py-1 rounded-full
                                               bg-indigo-100 text-indigo-700
                                               text-xs font-semibold">

                                        {{ $communication->total_students }}

                                    </span>

                                </td>


                                {{-- Submitted --}}
                                <td class="px-6 py-4">

                                    <div class="text-sm text-gray-700">

                                        {{ optional($communication->submitted_at)->format('d-m-Y') }}

                                    </div>

                                    <div class="text-xs text-gray-500">

                                        {{ optional($communication->submitted_at)->format('h:i A') }}

                                    </div>

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    <span
                                        class="inline-flex items-center
                                               px-2.5 py-1 rounded-full
                                               bg-yellow-100 text-yellow-700
                                               text-xs font-semibold">

                                        PENDING

                                    </span>

                                </td>


                                <!-- {{-- Actions --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- View --}}
                                        <button
                                            type="button"
                                            onclick="viewCommunication({{ $communication->id }})"
                                            class="px-3 py-2 rounded-lg
                                                   bg-gray-100 text-gray-700
                                                   text-xs font-medium
                                                   hover:bg-gray-200">

                                            View

                                        </button>


                                        {{-- Confirm --}}
                                        <button
                                            type="button"
                                            onclick="confirmCommunication({{ $communication->id }})"
                                            class="px-3 py-2 rounded-lg
                                                   bg-green-600 text-white
                                                   text-xs font-medium
                                                   hover:bg-green-700">

                                            Confirm

                                        </button>


                                        {{-- Reject --}}
                                        <button
                                            type="button"
                                            onclick="rejectCommunication({{ $communication->id }})"
                                            class="px-3 py-2 rounded-lg
                                                   bg-red-600 text-white
                                                   text-xs font-medium
                                                   hover:bg-red-700">

                                            Reject

                                        </button>

                                    </div>

                                </td> -->
                                {{-- Actions --}}
<td class="px-6 py-4">

    <div class="flex items-center justify-end gap-2">

        {{-- View --}}
        <button
            type="button"
            onclick="viewCommunication({{ $communication->id }})"
            class="px-3 py-2 rounded-lg
                   bg-gray-100 text-gray-700
                   text-xs font-medium
                   hover:bg-gray-200">
            View
        </button>

        {{-- Confirm --}}
        @if($communication->status === 'PENDING')
            <button
                type="button"
                onclick="confirmCommunication({{ $communication->id }})"
                class="px-3 py-2 rounded-lg
                       bg-green-600 text-white
                       text-xs font-medium
                       hover:bg-green-700">
                Confirm
            </button>
        @endif

        {{-- Send SMS --}}
        @if($communication->status === 'CONFIRMED')
            <button
                type="button"
                onclick="sendCommunication({{ $communication->id }})"
                class="px-3 py-2 rounded-lg
                       bg-blue-600 text-white
                       text-xs font-medium
                       hover:bg-blue-700">
                Send SMS
            </button>
        @endif

        {{-- Reject --}}
        @if($communication->status === 'PENDING')
            <button
                type="button"
                onclick="rejectCommunication({{ $communication->id }})"
                class="px-3 py-2 rounded-lg
                       bg-red-600 text-white
                       text-xs font-medium
                       hover:bg-red-700">
                Reject
            </button>
        @endif

    </div>

</td>

                            </tr>


                            {{-- Hidden message/details --}}
                            <tr
                                id="details-{{ $communication->id }}"
                                class="hidden bg-gray-50">

                                <td colspan="8" class="px-6 py-5">

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                                        {{-- Message --}}
                                        <div>

                                            <h3 class="text-sm font-semibold text-gray-700 mb-2">
                                                Message
                                            </h3>

                                            <div
                                                class="bg-white border
                                                       border-gray-200
                                                       rounded-lg p-4
                                                       text-sm text-gray-700
                                                       whitespace-pre-line">

                                                {{ $communication->message }}

                                            </div>

                                        </div>


                                        {{-- Students --}}
                                        <div>

                                            <h3 class="text-sm font-semibold text-gray-700 mb-2">
                                                Selected Students
                                            </h3>

                                            <div
                                                class="bg-white border
                                                       border-gray-200
                                                       rounded-lg
                                                       max-h-48
                                                       overflow-y-auto">

                                                @foreach($communication->recipients as $recipient)

                                                    <div
                                                        class="px-4 py-2
                                                               border-b
                                                               border-gray-100
                                                               text-sm">

                                                        <span class="font-medium">
                                                            {{ $recipient->student_name }}
                                                        </span>

                                                        @if($recipient->phone)

                                                            <span class="text-gray-500 ml-2">
                                                                {{ $recipient->phone }}
                                                            </span>

                                                        @else

                                                            <span class="text-red-500 ml-2">
                                                                No phone number
                                                            </span>

                                                        @endif

                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


        @else

            {{-- Empty --}}
            <div class="py-16 text-center">

                <div class="text-gray-400 text-4xl mb-3">
                    ✓
                </div>

                <h3 class="text-lg font-semibold text-gray-700">
                    No Pending Communications
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    There are no communications waiting for approval.
                </p>

            </div>

        @endif

    </div>

</div>


@push('scripts')

<script>

function viewCommunication(id)
{
    const row =
        document.getElementById('details-' + id);

    if (!row) {
        return;
    }

    row.classList.toggle('hidden');
}


/*
|--------------------------------------------------------------------------
| Confirm
|--------------------------------------------------------------------------
*/

function confirmCommunication(id)
{
    if (!confirm(
        'Are you sure you want to confirm this communication?'
    )) {
        return;
    }


    fetch(
        "{{ url('admin/parent-communications') }}/" +
        id +
        "/confirm",
        {
            method: 'POST',

            headers: {
                'Accept': 'application/json',

                'X-Requested-With': 'XMLHttpRequest',

                'X-CSRF-TOKEN':
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute('content')
            }
        }
    )

    .then(async response => {

        const data = await response.json();

        if (!response.ok) {

            throw new Error(
                data.message ||
                'Unable to confirm communication.'
            );
        }

        return data;

    })

    .then(data => {

        alert(
            data.message ||
            'Communication confirmed successfully.'
        );

        window.location.reload();

    })

    .catch(error => {

        console.error(error);

        alert(error.message);

    });
}
function sendCommunication(id)
{
    if (!confirm(
        'Are you sure you want to send SMS to all selected parents?'
    )) {
        return;
    }

    fetch(
        "{{ url('admin/parent-communications') }}/" +
        id +
        "/send",
        {
            method: 'POST',

            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN':
                    document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
            }
        }
    )
    .then(async response => {

        const data = await response.json();

        if (!response.ok) {
            throw new Error(
                data.message ||
                'Unable to send SMS.'
            );
        }

        return data;
    })
    .then(data => {

        alert(
            'SMS Result: Sent = ' +
            data.sent +
            ', Failed = ' +
            data.failed
        );

        window.location.reload();

    })
    .catch(error => {

        console.error(error);

        alert(error.message);

    });
}

/*
|--------------------------------------------------------------------------
| Reject
|--------------------------------------------------------------------------
*/

function rejectCommunication(id)
{
    if (!confirm(
        'Are you sure you want to reject this communication?'
    )) {
        return;
    }


    fetch(
        "{{ url('admin/parent-communications') }}/" +
        id +
        "/reject",
        {
            method: 'POST',

            headers: {
                'Accept': 'application/json',

                'X-Requested-With': 'XMLHttpRequest',

                'X-CSRF-TOKEN':
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute('content')
            }
        }
    )

    .then(async response => {

        const data = await response.json();

        if (!response.ok) {

            throw new Error(
                data.message ||
                'Unable to reject communication.'
            );
        }

        return data;

    })

    .then(data => {

        alert(
            data.message ||
            'Communication rejected successfully.'
        );

        window.location.reload();

    })

    .catch(error => {

        console.error(error);

        alert(error.message);

    });
}

</script>

@endpush

@endsection