<?php

namespace App\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Admin Login')]
class AdminLogin extends Component
{
    #[Validate('required|string')]
    public string $password = '';

    public function login(): void
    {
        $this->validate();

        if ($this->password === config('app.admin_password')) {
            session(['is_admin' => true]);
            $this->redirect(route('knowledge-bases.index'), navigate: true);
        } else {
            $this->addError('password', 'Incorrect password.');
        }
    }

    public function render(): View
    {
        return view('livewire.admin.admin-login');
    }
}