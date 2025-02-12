<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class HomePage extends Component
{   
    public function render()
    {
        return view('livewire.pages.home-page')->title('Home Page')->layout('components.layouts.app');
    }
}
