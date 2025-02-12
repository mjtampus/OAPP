<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Login extends Component
{
    public function render()
    {
        return view('livewire.pages.login')->layout('components.layouts.app')->title('Login');
    }
}
