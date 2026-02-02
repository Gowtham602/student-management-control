<div class="h-full flex flex-col px-4 py-6 bg-white border-r">

    {{-- Brand --}}
    <div class="flex items-center gap-2 mb-8 px-2">
        <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">
            E
        </div>
        <span class="text-lg font-semibold text-gray-800">
            EduAdmin
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">

            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m4 0h5a1 1 0 001-1V10"/>
            </svg>

            <span class="text-sm font-medium">Dashboard</span>
        </a>

        {{-- Students --}}
        <a href="{{ route('admin.students.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
           {{ request()->routeIs('admin.students.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">

            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>

            <span class="text-sm font-medium">Students</span>
        </a>

        {{-- Departments --}}
   <a href="{{ route('admin.departments.index') }}"
   class="flex items-center gap-3 px-3 py-2 rounded-lg transition
   {{ request()->routeIs('departments.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">

<svg class="w-5 h-5 text-current" viewBox="0 0 24 24" fill="currentColor">
    <path d="M3 22h18v-2H3v2zm2-4h14V4l-7-3-7 3v14zm4-2h2v-2H9v2zm0-4h2v-2H9v2zm4 4h2v-2h-2v2zm0-4h2v-2h-2v2z"/>
</svg>

<span class="text-sm font-medium">Departments</span>
<a href="{{ route('admin.sections.index') }}"
   class="flex items-center gap-3 px-3 py-2 rounded-lg transition
   {{ request()->routeIs('sections.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">

<svg class="w-5 h-5 text-current" viewBox="0 0 24 24" fill="currentColor">
    <path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/>
</svg>

<span class="text-sm font-medium">Sections</span>
</a>



    </nav>

    {{-- Footer --}}
    <div class="mt-auto px-2 text-xs text-gray-400">
        © {{ date('Y') }} EduAdmin
    </div>

</div>
