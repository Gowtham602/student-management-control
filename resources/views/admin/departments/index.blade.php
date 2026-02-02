@extends('layouts.admin')

@section('title','Departments')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Departments</h2>

        <a href="{{ route('admin.departments.create') }}"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Add Department
        </a>
    </div>


    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide">
                    <tr>
                        <th class="p-4 text-left">#</th>
                        <th class="p-4 text-left">Department</th>
                        <th class="p-4 text-left">Code</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($departments as $i => $d)
                    <tr class="hover:bg-indigo-50/40 transition">

                        <td class="p-4 text-gray-500">
                            {{ $departments->firstItem() + $i }}
                        </td>

                        <td class="p-4 font-semibold text-gray-800">
                            {{ $d->name }}
                        </td>

                        <td class="p-4">
                            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $d->code }}
                            </span>
                        </td>

                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-3">

                                {{-- View --}}
                                <!-- <a href="#"
                                    class="bg-emerald-100 text-emerald-600 p-2 rounded-xl hover:bg-emerald-200 transition group">

                                    <svg class="w-5 h-5 group-hover:scale-110 transition" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5
                         c4.477 0 8.268 2.943 9.542 7
                         -1.274 4.057-5.065 7-9.542 7
                         -4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a> -->

                                {{-- Edit --}}
                                <a href="{{ route('admin.departments.edit',$d) }}"
                                    class="bg-indigo-100 text-indigo-600 p-2 rounded-xl hover:bg-indigo-200 transition group">

                                    <svg class="w-5 h-5 group-hover:scale-110 transition" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5h2m2 0l4 4-9 9H6v-4l9-9z" />
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <!-- <form id="deleteForm" method="POST">
                                    @csrf
                                    @method('DELETE') -->

                                    {{-- Delete --}}
<button
    type="button"
    onclick="openDeleteModal({{ $d->id }})"
    class="bg-red-100 text-red-600 p-2 rounded-xl hover:bg-red-200 transition group">

    <svg class="w-5 h-5 group-hover:scale-110 transition"
         fill="none" stroke="currentColor" stroke-width="2"
         viewBox="0 0 24 24">

        <path stroke-linecap="round" stroke-linejoin="round"
              d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>


                                <!-- </form> -->

                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div id="deleteModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl animate-scale">

        <h3 class="text-lg font-semibold text-gray-800 mb-2">
            Delete Department?
        </h3>

        <p class="text-gray-500 mb-6">
            This action cannot be undone.
        </p>

        <div class="flex justify-end gap-3">

            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 rounded-lg border hover:bg-gray-100">
                Cancel
            </button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Delete
                </button>
            </form>

        </div>
    </div>
</div>

        </div>

    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $departments->links('pagination::tailwind') }}
    </div>


</div>
@push('scripts')
<script>

function openDeleteModal(id) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');

    form.action = "{{ url('admin/departments') }}/" + id;
    


    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

</script>
@endpush
@endsection