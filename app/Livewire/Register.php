<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component {
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    #[Validate('required|string|min:8')]
    public string $password_confirmation = '';

    public bool $isLoading = false;

    public function mount() {
        // Redirect if already authenticated
        if (Auth::check()) {
            return $this->redirect('/dashboard');
        }
    }

    public function register() {
        $this->isLoading = true;

        try {
            $this->validate();

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 2, // Default user role
            ]);

            Auth::login($user);
            session()->regenerate();

            // Add success message
            session()->flash('success', "Welcome {$this->name}! Your account has been created successfully.");

            return $this->redirect('/dashboard');
        } catch (\Exception $e) {
            $this->addError('email', 'An error occurred during registration. Please try again.');
        } finally {
            $this->isLoading = false;
        }
    }

    public function resetForm() {
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
        $this->resetErrorBag();
    }

    #[Layout('components.layouts.app')]
    #[Title('Register')]
    public function render() {
        return view('livewire.register');
    }
}
