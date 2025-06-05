<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component {
    public string $activeTab = 'dashboard';
    public bool $isLoggingOut = false;

    public function mount() {
        // Ensure user is authenticated
        if (!Auth::check()) {
            session()->flash('error', 'Please log in to access the dashboard.');
            return $this->redirect('/login');
        }
    }

    #[On('tab-changed')]
    public function handleTabChange($tab) {
        $this->activeTab = $tab;
    }

    public function logout() {
        $this->isLoggingOut = true;

        try {
            // Get user name before logout for message
            $userName = Auth::user()->name ?? 'User';

            // Logout user
            Auth::logout();

            // Invalidate session
            session()->invalidate();
            session()->regenerateToken();

            // Clear all session data
            session()->flush();

            // Add logout message
            session()->flash('success', "Goodbye {$userName}! You have been logged out successfully.");

            // Redirect to login page
            return $this->redirect('/login');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred during logout. Please try again.');
            $this->isLoggingOut = false;
        }
    }

    public function refreshDashboard() {
        // Method to refresh dashboard data
        $this->dispatch('$refresh');
    }

    #[Layout('components.layouts.app')]
    #[Title('Dashboard')]
    public function render() {
        return view('livewire.dashboard');
    }
}
