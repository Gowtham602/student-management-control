<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard')</title>

    {{-- Tailwind + Alpine --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific styles --}}
    @stack('styles')
</head>

<body class="bg-gray-100">

<div x-data="{ open: false }" class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside
        class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r
               transform transition-transform duration-200
               md:static md:translate-x-0"
        :class="open ? 'translate-x-0' : '-translate-x-full'">

        @include('components.admin.sidebar')
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col">

        {{-- NAVBAR --}}
        @include('components.admin.navbar')

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        @include('components.admin.footer')
    </div>
</div>

{{-- Global JS (once) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- Page-specific JS --}}
@stack('scripts')

<!-- //for toastmessage -->
@if(session('success'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: "{{ session('success') }}",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});
</script>
@endif
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content')
    }
});
</script>


</body>
</html>
