@extends('layouts.admin')

@section('title','Add Section')

@section('content')

<div class="max-w-lg bg-white p-6 rounded-xl shadow">

<h2 class="text-lg font-semibold mb-4">Add Section</h2>

<form method="POST" action="{{ route('admin.sections.store') }}">
@csrf

<div class="mb-4">
    <label class="block text-sm mb-1">Department</label>
    <select name="department_id" class="w-full border rounded px-3 py-2" required>
        <option value="">Select Department</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block text-sm mb-1">Section (A, B, C)</label>
    <input name="name" class="w-full border rounded px-3 py-2 uppercase" required>
</div>

<button class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">
Save Section
</button>

</form>
</div>

@endsection
