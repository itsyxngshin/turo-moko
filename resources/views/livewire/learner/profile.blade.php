<main class="p-0">

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl p-6 shadow-md mb-6 flex items-center space-x-6">
        <img 
            src="{{ Str::startsWith($user->profile_image, 'http') 
                    ? $user->profile_image 
                    : asset('storage/' . $user->profile_image) }}" 
            onerror="this.src='{{ asset('images/default-profile.png') }}';"
            alt="Profile" 
            class="w-24 h-24 rounded-full object-cover">

        <div class="flex-1">
            <h2 class="text-xl font-semibold">{{ $user->username }}</h2>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            <p class="text-gray-500 text-sm">Welcome to TURO-MOKO!</p>
        </div>
<div>
        <div>
            <button wire:click="$set('showProfileModal', true)" 
                    class="px-4 py-2 bg-black text-white rounded-full text-sm hover:bg-gray-800 transition">
                Edit Profile
            </button>
        </div>
    </div>

    <!-- Modal -->
@if($showProfileModal)
<div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 !m-0 !p-0 w-full">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-5xl p-10 relative">

        <!-- Close Button -->
        <button wire:click="$set('showProfileModal', false)"
            class="absolute top-6 right-6 text-gray-500 hover:text-gray-700 text-xl">✕</button>

        <h2 class="text-3xl font-bold mb-10 text-gray-800">Edit Implementor</h2>

        <!-- FORM -->
        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <!-- LEFT: IMAGE UPLOAD -->
            <div class="flex flex-col items-center">
                <label
                    class="w-40 h-40 rounded-full bg-gray-100 flex items-center justify-center cursor-pointer overflow-hidden border text-gray-500 hover:bg-gray-200 transition">

                    @if($newProfileImage)
                        <img src="{{ $newProfileImage->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-sm">Click to upload</span>
                    @endif

                    <input type="file" class="hidden" wire:model="newProfileImage" accept="image/*">
                </label>

                <p class="text-xs text-gray-400 mt-2">Max 1MB, JPEG/PNG</p>

                @error('newProfileImage')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- RIGHT: FORM FIELDS -->
            <div class="md:col-span-2 grid grid-cols-2 gap-6">

                <!-- First Name -->
                <div>
                    <label class="text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" wire:model.defer="first_name"
                        class="w-full mt-1 p-3 border rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>

                <!-- Middle Name -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Middle Name</label>
                    <input type="text" wire:model.defer="middle_name"
                        class="w-full mt-1 p-3 border rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>

                <!-- Last Name -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" wire:model.defer="last_name"
                        class="w-full mt-1 p-3 border rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>

                <!-- Phone -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" wire:model.defer="phone"
                        class="w-full mt-1 p-3 border rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>

                <!-- Email -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model.defer="email"
                        class="w-full mt-1 p-3 border rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>

                <!-- Username -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Username</label>
                    <input type="text" wire:model.defer="username"
                        class="w-full mt-1 p-3 border rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>

                <!-- Password -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Password</label>
                    <input type="password" wire:model.defer="password"
                        class="w-full mt-1 p-3 border rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" wire:model.defer="password_confirmation"
                        class="w-full mt-1 p-3 border rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="md:col-span-3 flex justify-end mt-6 gap-4">
                <button type="button" wire:click="$set('showProfileModal', false)"
                    class="px-8 py-3 rounded-full border border-gray-300 hover:bg-gray-100">
                    Cancel
                </button>

                <button type="submit"
                    class="px-8 py-3 rounded-full bg-orange-400 text-white font-semibold hover:bg-orange-500">
                    Update
                </button>
            </div>

        </form>

    </div>
</div>
@endif


</div>

    <!-- Stats -->
    <div class="flex space-x-4 mb-6">
        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md text-center cursor-pointer hover:bg-gray-50"
            onclick="window.location='{{ route('learner.courses.index') }}'">
            <div class="text-2xl mb-2 text-indigo-600">📘</div>
            <h3 class="font-semibold">Active courses</h3>
            <p class="text-gray-500 text-sm">{{ $activeCourses }}</p>
        </div>

        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md text-center cursor-pointer hover:bg-gray-50"
            onclick="window.location='{{ route('learner.archived-courses') }}'">
            <div class="text-2xl mb-2 text-yellow-600">📁</div>
            <h3 class="font-semibold">Archived courses</h3>
            <p class="text-gray-500 text-sm">{{ $archivedCourses }}</p>
        </div>
    </div>

    <!-- Courses Grid -->
    <section class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Your courses</h3>
            <button wire:click="$refresh" class="text-sm text-indigo-600 hover:underline">Refresh</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($courses as $course)
                <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border h-40">
                    <img src="{{ $course['image'] }}" alt="{{ $course['name'] }}" class="w-40 h-full object-cover">
                    <div class="flex-1 p-5 flex flex-col justify-between">
                        <div>
                            <p class="text-xs text-gray-400">{{ $course['semester'] }}</p>
                            <h4 class="font-semibold text-sm">{{ $course['name'] }}</h4>
                            <p class="text-sm text-gray-500 leading-snug line-clamp-2">{{ $course['description'] }}</p>
                        </div>

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
