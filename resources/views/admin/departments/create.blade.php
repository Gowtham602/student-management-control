@extends('layouts.admin')

@section('title','Department')

@section('content')

<div class="max-w-lg bg-white p-6 rounded-xl shadow">

    <h2 class="text-lg font-semibold mb-4">
        {{ isset($department) ? 'Edit Department' : 'Add Department' }}
    </h2>

    <form method="POST"
          action="{{ isset($department) ? route('admin.departments.update',$department) : route('admin.departments.store') }}">
        @csrf
        @if(isset($department)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-sm mb-1">Name</label>
            <input name="name" value="{{ old('name',$department->name ?? '') }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Code</label>
            <input name="code" value="{{ old('code',$department->code ?? '') }}"
                   class="w-full border rounded px-3 py-2 uppercase" required>
        </div>

        <button class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">
            Save
        </button>
    </form>

</div>

@endsection
