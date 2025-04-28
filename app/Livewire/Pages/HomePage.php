<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Homepage')]
#[Layout('components.layouts.app')]
class HomePage extends Component
{   
    public function render()
    {
        return view('livewire.pages.home-page');
    }
}
