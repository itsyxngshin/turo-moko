@extends('layouts.layout') 
@section('content')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
  <div class="max-w-[1720px] mx-auto px-6 pb-16">
      <!-- Stats Row -->
      <section class="mt-8 grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Enrollees -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-6">
          <div class="w-20 h-20 rounded-full bg-gray-300/30 grid place-items-center">
            <img src="/img/vector-22.svg" alt="icon" class="w-10 h-10" />
          </div>
          <div>
            <h3 class="text-2xl font-semibold">Enrollees</h3>
            <p class="text-xl text-gray-600 tracking-wide">1,234</p>
          </div>
        </div>

        <!-- Implementors -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-6">
          <div class="w-20 h-20 rounded-full bg-gray-300/20 grid place-items-center">
            <img src="/img/fluent-emoji-flat-briefcase.svg" alt="briefcase" class="w-10 h-10" />
          </div>
          <div>
            <h3 class="text-2xl font-semibold">Implementors</h3>
            <p class="text-xl text-gray-600 tracking-wide">1,234</p>
          </div>
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-6">
          <div class="w-20 h-20 rounded-full bg-gray-300/20 grid place-items-center">
            <img src="/img/noto-books.svg" alt="books" class="w-10 h-10" />
          </div>
          <div>
            <h3 class="text-2xl font-semibold">Courses</h3>
            <p class="text-xl text-gray-600 tracking-wide">1,234</p>
          </div>
        </div>

        <!-- Overall Donut Card -->
        <div class="bg-white rounded-2xl shadow-md p-6">
          <h3 class="text-2xl font-medium">Overall</h3>

          <!-- Donut chart with two rings -->
          <div class="mt-4 relative w-40 h-40 mx-auto">
            <!-- Outer track -->
            <svg viewBox="0 0 40 40" class="w-40 h-40 rotate-[-90deg]">
              <circle cx="20" cy="20" r="18" fill="none" stroke="#E5F6FE" stroke-width="4" />
              <!-- Outer progress (Active Students 86%) -->
              <circle cx="20" cy="20" r="18" fill="none" stroke="#8BDCFC" stroke-width="4" stroke-dasharray="113" stroke-dashoffset="18" stroke-linecap="round" />
            </svg>
            <!-- Inner ring -->
            <div class="absolute inset-0 grid place-items-center">
              <svg viewBox="0 0 36 36" class="w-28 h-28 rotate-[-90deg]">
                <circle cx="18" cy="18" r="16" fill="none" stroke="#E5EEF2" stroke-width="4" />
                <!-- Inner progress (Active Mentors 78%) -->
                <circle cx="18" cy="18" r="16" fill="none" stroke="#A0BDCB" stroke-width="4" stroke-dasharray="100.5" stroke-dashoffset="22" stroke-linecap="round" />
              </svg>
            </div>
          </div>

          <!-- Labels -->
          <div class="mt-4 flex items-start justify-between gap-4 px-2">
            <div>
              <div class="text-3xl font-medium leading-none">86%</div>
              <div class="text-sm text-black/80">Active Students</div>
            </div>
            <div class="text-right">
              <div class="text-3xl font-medium leading-none">78%</div>
              <div class="text-sm text-black/80">Active Mentors</div>
            </div>
          </div>
        </div>
      </section>

      <!-- Courses List -->
      <section class="mt-10 bg-white rounded-2xl shadow-md border border-black/20">
        <div class="flex items-center justify-between px-6 py-4">
          <h2 class="text-2xl md:text-[28px] font-semibold">All courses</h2>
          <a href="#" class="inline-flex items-center justify-center h-10 px-6 rounded-full border border-gray-300 text-base bg-white hover:bg-gray-50">View all</a>
        </div>

        <div class="px-6 pb-8 grid grid-cols-1 xl:grid-cols-2 gap-6">
          <!-- Card template (repeat x6) -->
          <!-- Card 1 -->
          <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden">
            <button class="absolute top-3 right-3 w-8 h-8 grid place-items-center rounded-full hover:bg-gray-100" aria-label="menu">
              <img src="/img/vector-7.svg" alt="more" class="w-5 h-5" />
            </button>
            <div class="grid grid-cols-[360px,1fr] gap-6 p-4">
              <img src="/img/tran-mau-tri-tam-tznbaktucti-unsplash-digital-learning-2.png" alt="Course cover" class="w-[356px] h-[200px] object-cover rounded-xl" />
              <div class="pr-4">
                <h3 class="text-lg md:text-xl font-medium text-black">Course Name</h3>
                <p class="mt-1 text-sm text-gray-600">1st Sem SY 2024-2025</p>
                <p class="mt-3 text-sm text-black/80">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="mt-4">
                  <button class="inline-flex h-7 px-5 rounded-full bg-black text-white text-xs font-medium">Start</button>
                </div>
              </div>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden">
            <button class="absolute top-3 right-3 w-8 h-8 grid place-items-center rounded-full hover:bg-gray-100" aria-label="menu">
              <img src="/img/vector.svg" alt="more" class="w-5 h-5" />
            </button>
            <div class="grid grid-cols-[360px,1fr] gap-6 p-4">
              <img src="/img/41230590a0ff35dd78f8856bd993affb-1.png" alt="Course cover" class="w-[358px] h-[202px] object-cover rounded-xl" />
              <div class="pr-4">
                <h3 class="text-lg md:text-xl font-medium text-black">Course Name</h3>
                <p class="mt-1 text-sm text-gray-600">1st Sem SY 2024-2025</p>
                <p class="mt-3 text-sm text-black/80">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="mt-4">
                  <button class="inline-flex h-7 px-5 rounded-full bg-black text-white text-xs font-medium">Start</button>
                </div>
              </div>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden">
            <button class="absolute top-3 right-3 w-8 h-8 grid place-items-center rounded-full hover:bg-gray-100" aria-label="menu">
              <img src="/img/vector-27.svg" alt="more" class="w-5 h-5" />
            </button>
            <div class="grid grid-cols-[360px,1fr] gap-6 p-4">
              <img src="/img/image-3.png" alt="Course cover" class="w-[365px] h-[180px] object-cover rounded-xl" />
              <div class="pr-4">
                <h3 class="text-lg md:text-xl font-medium text-black">Course Name</h3>
                <p class="mt-1 text-sm text-gray-600">1st Sem SY 2024-2025</p>
                <p class="mt-3 text-sm text-black/80">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="mt-4">
                  <button class="inline-flex h-7 px-5 rounded-full bg-black text-white text-xs font-medium">Start</button>
                </div>
              </div>
            </div>
          </article>

          <!-- Card 4 -->
          <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden">
            <button class="absolute top-3 right-3 w-8 h-8 grid place-items-center rounded-full hover:bg-gray-100" aria-label="menu">
              <img src="/img/vector-16.svg" alt="more" class="w-5 h-5" />
            </button>
            <div class="grid grid-cols-[360px,1fr] gap-6 p-4">
              <img src="/img/image-4.png" alt="Course cover" class="w-[361px] h-[182px] object-cover rounded-xl" />
              <div class="pr-4">
                <h3 class="text-lg md:text-xl font-medium text-black">Course Name</h3>
                <p class="mt-1 text-sm text-gray-600">1st Sem SY 2024-2025</p>
                <p class="mt-3 text-sm text-black/80">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="mt-4">
                  <button class="inline-flex h-7 px-5 rounded-full bg-black text-white text-xs font-medium">Start</button>
                </div>
              </div>
            </div>
          </article>

          <!-- Card 5 -->
          <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden">
            <button class="absolute top-3 right-3 w-8 h-8 grid place-items-center rounded-full hover:bg-gray-100" aria-label="menu">
              <img src="/img/vector-5.svg" alt="more" class="w-5 h-5" />
            </button>
            <div class="grid grid-cols-[360px,1fr] gap-6 p-4">
              <img src="/img/image-5.png" alt="Course cover" class="w-[365px] h-[201px] object-cover rounded-xl" />
              <div class="pr-4">
                <h3 class="text-lg md:text-xl font-medium text-black">Course Name</h3>
                <p class="mt-1 text-sm text-gray-600">1st Sem SY 2024-2025</p>
                <p class="mt-3 text-sm text-black/80">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="mt-4">
                  <button class="inline-flex h-7 px-5 rounded-full bg-black text-white text-xs font-medium">Start</button>
                </div>
              </div>
            </div>
          </article>

          <!-- Card 6 -->
          <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden">
            <button class="absolute top-3 right-3 w-8 h-8 grid place-items-center rounded-full hover:bg-gray-100" aria-label="menu">
              <img src="/img/vector-17.svg" alt="more" class="w-5 h-5" />
            </button>
            <div class="grid grid-cols-[360px,1fr] gap-6 p-4">
              <img src="/img/image-6.png" alt="Course cover" class="w-[362px] h-[201px] object-cover rounded-xl" />
              <div class="pr-4">
                <h3 class="text-lg md:text-xl font-medium text-black">Course Name</h3>
                <p class="mt-1 text-sm text-gray-600">1st Sem SY 2024-2025</p>
                <p class="mt-3 text-sm text-black/80">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="mt-4">
                  <button class="inline-flex h-7 px-5 rounded-full bg-black text-white text-xs font-medium">Start</button>
                </div>
              </div>
            </div>
          </article>
        </div>
      </section>
  </div>

@endsection

