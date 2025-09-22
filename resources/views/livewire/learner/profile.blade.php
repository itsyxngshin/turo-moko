<main class="p-0">

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl p-6 shadow-md mb-6 flex items-center space-x-6">
        <img src="{{ auth()->user()->profile_image ?? '/images/banner.jpg' }}" 
             alt="Profile" class="w-24 h-24 rounded-full object-cover">
        <div>
            <h2 class="text-xl font-semibold">{{ $studentName }}</h2>
            <p class="text-gray-500 text-sm">{{ $description }}</p>
            <p class="text-gray-500 text-sm">{{ $email }}</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="flex space-x-4 mb-6">
        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md text-center">
            <div class="text-2xl mb-2 text-indigo-600">📘</div>
            <h3 class="font-semibold">Active courses</h3>
            <p class="text-gray-500 text-sm">{{ $activeCourses }}</p>
        </div>
        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md text-center">
            <div class="text-2xl mb-2 text-yellow-600">📁</div>
            <h3 class="font-semibold">Archived courses</h3>
            <p class="text-gray-500 text-sm">{{ $archivedCourses }}</p>
        </div>
    </div>

    <!-- Courses Grid -->
    <section class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Your courses</h3>
            <button class="text-sm text-indigo-600 hover:underline">View all</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($courses as $course)
            <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border h-40">
                <!-- Course Image -->
                <img src="{{ $course['image'] }}" alt="{{ $course['name'] }}" class="w-40 h-full object-cover">
                
                <!-- Course Info -->
                <div class="flex-1 p-5 flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-gray-400">{{ $course['semester'] }}</p>
                        <h4 class="font-semibold text-sm">{{ $course['name'] }}</h4>
                        <p class="text-sm text-gray-500 leading-snug line-clamp-2">
                            {{ $course['description'] }}
                        </p>
                    </div>

                    <!-- Progress & Button -->
                    <div class="mt-4 flex items-center gap-4">
                        <div class="flex items-center gap-2 w-full">
                            <span class="text-xs text-gray-500">{{ $course['progress'] }}%</span>
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-black" style="width: {{ $course['progress'] }}%;"></div>
                            </div>
                        </div>
                        <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
                            Continue
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

</main>
