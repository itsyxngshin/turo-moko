<div class="px-8 py-4 flex items-center gap-4 bg-transparent">
    <h1 class="text-xl font-semibold">
        @if(View::hasSection('page-title'))
            @yield('page-title')
        @else
            Hello, Student!
        @endif
    </h1>
</div>
