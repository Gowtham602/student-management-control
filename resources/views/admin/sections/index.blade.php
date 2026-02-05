@extends('layouts.admin')

@section('title','Sections')

@section('content')

<div class="bg-white rounded-2xl shadow border overflow-hidden">

    <!-- Header -->
    <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">
            Sections
        </h2>

        <a href="{{ route('admin.sections.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
             Add Section
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Department</th>
                    <th class="px-6 py-4">Section Name</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($sections as $s)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $s->department->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $s->name }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-6 text-center text-gray-500">
                            No sections found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
