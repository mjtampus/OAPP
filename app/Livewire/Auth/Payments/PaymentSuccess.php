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
        $hasPaymongoSession = session()->has('paymongo_sessionId');
        $hasStripeSession = session()->has('stripe_checkout_id');
        $hasCodSession = session()->has('cod_success');

        if (! $hasPaymongoSession && ! $hasStripeSession && ! $hasCodSession) {
            return redirect()->route('home'); // Redirect if no known sessions exist
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
        if (session()->has('cod_success')) {
            session()->forget('cod_success');
        }
    }

    public function render()
    {
        return view('livewire.auth.payments.payment-success')->layout('components.layouts.app');
    }
}
