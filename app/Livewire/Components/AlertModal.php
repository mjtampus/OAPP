<?php

namespace App\Livewire\Components;

use Livewire\Component;

class AlertModal extends Component
{
    public $showModal = false;
    public $message;
    public $callback;
    public $callbackData;

    protected $listeners = ['openModal'];

    public function openModal($message, $callback ,$callbackData = null)
    {
        $this->message = $message;
        $this->callback = $callback;
        $this->callbackData = $callbackData;

        // dd($message, $callback, $callbackData);
        $this->showModal = true;

        
    }

    public function confirm()
    {
        $this->dispatch($this->callback , $this->callbackData);
        $this->showModal = false;
    }

    public function cancel()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.components.alert-modal');
    }

}
