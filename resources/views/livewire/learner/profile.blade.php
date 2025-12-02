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
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">

            <!-- Close -->
            <button wire:click="$set('showProfileModal', false)" 
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✕</button>

            <h2 class="text-xl font-semibold text-center mb-4">Edit Profile</h2>

            <form wire:submit.prevent="save" class="flex flex-col gap-4">

                <!-- Profile Image -->
                <div class="flex items-center gap-4">
                    <img src="{{ $newProfileImage ? $newProfileImage->temporaryUrl() : ($user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-profile.png')) }}" 
                         class="w-16 h-16 rounded-full object-cover" alt="Profile">

                    <input type="file" wire:model="newProfileImage" accept="image/*">
                    @error('newProfileImage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Username -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Username</label>
                    <input type="text" wire:model.defer="username" 
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-black focus:ring-black p-3">
                    @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model.defer="email" 
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-black focus:ring-black p-3">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" wire:model.defer="phone" 
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-black focus:ring-black p-3">
                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Bio / Description</label>
                    <textarea wire:model.defer="bio" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-black focus:ring-black p-3" rows="3"></textarea>
                    @error('bio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" wire:model.defer="password" placeholder="••••••••"
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-black focus:ring-black p-3">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" wire:click="$set('showProfileModal', false)" 
                            class="px-6 py-2 border border-gray-300 rounded-full hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">Save</button>
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
