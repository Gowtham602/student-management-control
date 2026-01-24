<header class="bg-white border-b px-6 py-4 flex items-center justify-between">

    {{-- Mobile menu --}}
    <button @click="open = !open"
        class="md:hidden p-2 rounded-lg hover:bg-gray-100">
        <!-- Menu Icon -->
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Page title --}}
    <h1 class="text-lg font-semibold text-gray-800 tracking-tight">
        @yield('title','Admin Dashboard')
    </h1>

    {{-- Right section --}}
    <div class="flex items-center gap-4">

        {{-- Notification --}}
        <!-- <button class="p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11
                         a6 6 0 10-12 0v3.2
                         c0 .5-.2 1-.6 1.4L4 17h5"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 17a3 3 0 006 0"/>
            </svg>
        </button> -->

        {{-- User --}}
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white
                        flex items-center justify-center text-sm font-semibold">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>

            <span class="text-sm font-medium text-gray-700">
                {{ auth()->user()->name }}
            </span>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-red-600 hover:underline">
                Logout
            </button>
        </form>

    </div>
</header>
