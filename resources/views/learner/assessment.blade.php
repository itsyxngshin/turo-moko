@extends('layouts.layout5')

@section('content')
<div class="bg-white min-h-screen text-gray-800 relative">

    {{-- Fixed Header --}}
    <div class="fixed top-0 left-0 right-0 bg-white shadow-sm border-b border-orange-200 z-50">
        <div class="max-w-5xl mx-auto flex items-center justify-between px-6 py-3">
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-orange-700">Module Assessment</h1>
                <p class="text-xs text-gray-500">Introduction to Information Security</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-orange-700 bg-orange-50 px-3 py-1.5 rounded-full border border-orange-200">
                    20 Items
                </span>
            </div>
        </div>
    </div>

    {{-- Spacer for fixed header --}}
    <div class="h-24"></div>

    {{-- Scrollable Question Navigator --}}
    <div class="w-full overflow-x-auto no-scrollbar border-b border-orange-100 bg-white shadow-sm sticky top-16 z-40">
        <div class="max-w-5xl mx-auto flex space-x-2 py-3 px-4 min-w-max">
            @for ($i = 1; $i <= 20; $i++)
                <div class="question-box w-10 h-10 flex items-center justify-center rounded-md border text-sm font-semibold cursor-pointer transition duration-150
                    @if ($i === 1)
                        bg-orange-600 text-white border-orange-600
                    @else
                        bg-white text-gray-700 border-gray-300 hover:bg-orange-100
                    @endif"
                    data-question="{{ $i }}">
                    {{ $i }}
                </div>
            @endfor
        </div>
    </div>

    {{-- Questions Container --}}
    <div class="max-w-5xl mx-auto p-0 space-y-6">
        @php
            $questions = [
                'What is the primary goal of information security?',
                'Define confidentiality in the context of cybersecurity.',
                'What is the difference between encryption and hashing?',
                'Name one common type of malware.',
                'What is a firewall used for?',
                'Explain the concept of “phishing.”',
                'What is two-factor authentication (2FA)?',
                'Why is data backup important?',
                'Describe the role of a digital signature.',
                'What does CIA triad stand for in cybersecurity?',
                'What is malware short for?',
                'Give an example of a strong password.',
                'What is the purpose of a VPN?',
                'Define “data integrity.”',
                'Name one example of social engineering.',
                'What is the purpose of antivirus software?',
                'What is the meaning of “threat actor”?',
                'What does SSL stand for?',
                'Define “network security.”',
                'What is data encryption used for?',
            ];
        @endphp

        @foreach ($questions as $index => $question)
            <div class="question-card @if($index === 0) block @else hidden @endif border border-orange-100 bg-orange-50/40 rounded-xl p-6" data-index="{{ $index }}">
                <h3 class="font-semibold text-gray-800 mb-4">{{ $index + 1 }}. {{ $question }}</h3>

                {{-- Multiple choice sample --}}
                <div class="space-y-2 text-gray-700">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="q{{ $index }}" class="text-orange-600 focus:ring-orange-400">
                        <span>Option A</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="q{{ $index }}" class="text-orange-600 focus:ring-orange-400">
                        <span>Option B</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="q{{ $index }}" class="text-orange-600 focus:ring-orange-400">
                        <span>Option C</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="q{{ $index }}" class="text-orange-600 focus:ring-orange-400">
                        <span>Option D</span>
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Navigation Buttons --}}
    <div class="max-w-5xl mx-auto flex justify-between mt-10 pb-10">
        <button id="prevBtn" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg shadow-sm transition" disabled>
            ← Previous
        </button>
        <button id="nextBtn" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg shadow-md transition">
            Next →
        </button>
    </div>

    {{-- Footer --}}
    <div class="max-w-5xl mx-auto text-center mt-6 text-gray-500 text-sm pb-6">
        © {{ date('Y') }} TURO-MOKO E-Learning Platform — All Rights Reserved.
    </div>
</div>

{{-- JS for navigation --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.question-card');
        const boxes = document.querySelectorAll('.question-box');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let current = 0;

        function updateQuestion() {
            cards.forEach((card, i) => card.classList.toggle('hidden', i !== current));
            boxes.forEach((box, i) => {
                if (i === current) {
                    box.classList.add('bg-orange-600', 'text-white', 'border-orange-600');
                    box.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                    box.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                } else {
                    box.classList.remove('bg-orange-600', 'text-white', 'border-orange-600');
                    box.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                }
            });
            prevBtn.disabled = current === 0;
            nextBtn.textContent = current === cards.length - 1 ? 'Submit →' : 'Next →';
        }

        nextBtn.addEventListener('click', () => {
            if (current < cards.length - 1) {
                current++;
                updateQuestion();
            } else {
                alert('Assessment submitted!');
            }
        });

        prevBtn.addEventListener('click', () => {
            if (current > 0) {
                current--;
                updateQuestion();
            }
        });

        boxes.forEach((box, i) => {
            box.addEventListener('click', () => {
                current = i;
                updateQuestion();
            });
        });

        updateQuestion();
    });
</script>

<style>
    /* Hide scrollbar for clean look */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
