@extends('layouts.main') 
@section('title', 'Verify')

@section('content')
<div class="flex items-center justify-center h-screen w-screen bg-gray-50">
    <div class="bg-white shadow-lg rounded-xl p-6 w-96 text-center">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Mobile Phone Verification</h2>
        <p class="text-gray-500 mb-6">Enter the 4-digit verification code that was sent to your phone number.</p>

        <form wire:submit.prevent="verify" class="space-y-4">
            <div class="flex justify-center gap-3">
                @foreach($code as $index => $digit)
                    <input 
                        type="text" 
                        maxlength="1"
                        wire:model.lazy="code.{{ $index }}"
                        class="w-12 h-12 text-center border rounded-lg text-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                @endforeach
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
            >
                Verify Account
            </button>
        </form>

        <div class="mt-4">
            <p class="text-sm text-gray-500">
                Didn’t receive code? 
                <button wire:click="resend" class="text-blue-600 hover:underline">Resend</button>
            </p>
        </div>

        @if(session()->has('success'))
            <p class="mt-4 text-green-600">{{ session('success') }}</p>
        @endif

        @if(session()->has('error'))
            <p class="mt-4 text-red-600">{{ session('error') }}</p>
        @endif
    </div>
</div>
@endsection

