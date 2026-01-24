<header class="bg-white shadow p-4 flex justify-between items-center">
    <button @click="open = !open" class="md:hidden text-xl">☰</button>

    <h1 class="font-semibold">Admin Panel</h1>

    <div class="flex items-center gap-3">
        <span class="text-sm">{{ auth()->user()->name }}</span>
    </div>
</header>
