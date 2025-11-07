<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        // Use the 'web' guard if you're using multiple guards
        Auth::guard('web')->logout();

        // Invalidate the session
        request()->session()->invalidate();

        // Regenerate the session token
        request()->session()->regenerateToken();
        return $this->redirect('/login', navigate: true);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        // You can render a view or an inline blade template
        return <<<'BLADE'
            <button wire:click="logout" class="text-sm text-gray-700 underline">
                {{ __('Log Out') }}
            </button>
        BLADE;
    }
}
