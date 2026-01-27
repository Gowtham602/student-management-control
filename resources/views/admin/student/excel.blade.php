@extends('layouts.admin')

@section('title','Import Students')

@section('content')

<form method="POST"
      action="{{ route('admin.students.import') }}"
      enctype="multipart/form-data">
    @csrf
    <a href="{{ route('students.template') }}"
   class="btn btn-secondary">
   Download CSV Template
</a>


    <input type="file" name="file" required class="input">
    <button class="btn btn-primary mt-2">Upload CSV</button>
</form>

@if(session('failures') && count(session('failures')) > 0)
    <div class="mt-6">
        <h3 class="text-red-600 font-semibold mb-2">Import Errors</h3>

        <table class="w-full border text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-2 py-1">Row</th>
                    <th class="border px-2 py-1">Field</th>
                    <th class="border px-2 py-1">Error</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('failures') as $failure)
                    <tr>
                        <td class="border px-2 py-1">{{ $failure->row() }}</td>
                        <td class="border px-2 py-1">{{ $failure->attribute() }}</td>
                        <td class="border px-2 py-1 text-red-600">
                            {{ implode(', ', $failure->errors()) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
