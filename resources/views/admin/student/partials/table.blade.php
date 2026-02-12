<div class="overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">#</th>
                <th class="px-6 py-3 text-left">Roll No</th>
                <th class="px-6 py-3 text-left">Student</th>
                <th class="px-6 py-3 text-left">Department</th>
                <th class="px-6 py-3 text-left">Section</th>
                <th class="px-6 py-3 text-left">Year</th>
                <th class="px-6 py-3 text-left">Phone</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($students as $student)
                <tr class="hover:bg-indigo-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-700">
                        <!-- {{ $loop->iteration }} -->
                          <!-- {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }} -->
                            {{ $students->firstItem() + $loop->index }}


                    </td>

                    <td class="px-6 py-4">
                        {{ $student->rollnum }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-800">
                            {{ $student->name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $student->email }}
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->department->code ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->section->name ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->study_year }}
                        {{-- OR --}}
                        {{-- {{ now()->year - $student->admission_year + 1 }} --}}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->phone }}
                    </td>

                    <td class="px-6 py-4 text-right">
    <div class="flex justify-end gap-4">

        <!-- View / Info -->
        <a href="{{ route('admin.students.show', $student->id) }}"
           class="text-emerald-600 hover:text-emerald-800 transition hover:scale-110">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12
                         18 19.5 12 19.5 2.25 12 2.25 12z"/>
                <circle cx="12" cy="12" r="3.5"/>
            </svg>
        </a>

        <!-- Edit -->
        <a href="{{ route('admin.students.edit', $student->id) }}"
           class="text-indigo-600 hover:text-indigo-800 transition hover:scale-110">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.862 3.487a2.1 2.1 0 013.651 1.487
                         L7.5 18.987 3 21l2.013-4.5L16.862 3.487z"/>
            </svg>
        </a>

        <!-- Delete -->
        <button onclick="deleteStudent({{ $student->id }})"
                class="text-red-600 hover:text-red-800 transition hover:scale-110">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 7h16M9 7V4h6v3
                         M6 7l1 13h10l1-13
                         M10 11v6M14 11v6"/>
            </svg>
        </button>

    </div>
</td>

                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-gray-500">
                        No students found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
