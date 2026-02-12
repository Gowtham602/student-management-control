@extends('layouts.admin')

@section('title','Import Students')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- Upload Card -->
    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            Import Students via CSV
        </h2>

        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.students.template.csv') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                Download CSV Template
            </a>
        </div>

        <form method="POST"
              action="{{ route('admin.students.import') }}"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            <input type="file"
                   name="file"
                   required
                   class="w-full border rounded-lg p-2">

            <button class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                Upload CSV
            </button>
        </form>

        @if(session('success'))
            <div class="mt-4 bg-green-100 text-green-700 px-4 py-2 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Error Table -->    
     @if(session('summary'))
<div class="bg-green-100 p-4 rounded mb-4">
    Inserted: {{ session('summary.inserted') }} |
    Updated: {{ session('summary.updated') }}
</div>
@endif

    @if(session('failures') && count(session('failures')) > 0)
    <div class="bg-white shadow rounded-xl p-5 mt-6">

        <h3 class="text-red-600 font-semibold mb-3">
            Import Errors Found
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border rounded">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border px-3 py-2">Row</th>
                        <th class="border px-3 py-2">Field</th>
                        <th class="border px-3 py-2">Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('failures') as $failure)
                    <tr class="hover:bg-red-50">
                        <td class="border px-3 py-2 font-medium">
                            {{ $failure->row() }}
                        </td>
                        <td class="border px-3 py-2 capitalize">
                            {{ $failure->attribute() }}
                        </td>
                        <td class="border px-3 py-2 text-red-600">
                            {{ implode(', ', $failure->errors()) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

@endsection
