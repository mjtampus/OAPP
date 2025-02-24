<?php

namespace App\Livewire\Auth\Payments;

use Livewire\Component;

class PaymentSuccess extends Component
{
    public $timeLeft = 10;
    
    public function mount()
    {
        $this->dispatch('startTimer');
        $this->check_session();
    }

    public function check_session()
    {
        if (!session()->has('paymongo_sessionId') && !session()->has('stripe_checkout_id')) {
            return redirect()->route('home'); // Redirect if both sessions are missing
        }

        $this->forget_session(); // Forget the session if it exists before proceeding
    }

    public function forget_session()
    {
        if (session()->has('paymongo_sessionId')) {
            session()->forget('paymongo_sessionId');
        }
        if (session()->has('stripe_checkout_id')) {
            session()->forget('stripe_checkout_id');
        }
    }

    public function render()
    {
        return view('livewire.auth.payments.payment-success')->layout('components.layouts.app');
    }
}
