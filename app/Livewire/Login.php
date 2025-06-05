<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component {
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;
    public bool $isLoading = false;

    public function mount() {
        // Redirect if already authenticated
        if (Auth::check()) {
            return $this->redirect('/dashboard', navigate: true);
        }
    }

    public function login() {
        $this->isLoading = true;

        try {
            $this->validate();

            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                session()->regenerate();

                // Clear any old session data
                session()->forget('errors');

                // Add success message
                session()->flash('success', 'Welcome back!');

                // Use a more reliable redirect method
                return $this->redirect('/dashboard');
            } else {
                $this->addError('email', 'The provided credentials do not match our records.');
            }
        } catch (\Exception $e) {
            $this->addError('email', 'An error occurred during login. Please try again.');
        } finally {
            $this->isLoading = false;
        }
    }

    public function resetForm() {
        $this->reset(['email', 'password', 'remember']);
        $this->resetErrorBag();
    }

    #[Layout('components.layouts.app')]
    #[Title('Login')]
    public function render() {
        return view('livewire.login');
    }
}
