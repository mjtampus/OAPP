<?php

namespace App\Livewire\Pages;

use App\Models\User;
use App\Models\Carts;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Login extends Component
{
    public $name;
    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])){
            session()->regenerate();

            $user = auth()->user();

            return redirect()->route('home');
        }

        $this->dispatch('notify', [
            'message' => 'Invalid credentials',
            'type' => 'error'
        ]);
    }

    
    public function register()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
    
        // Register user
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
    
        // Log the user in
        Auth::login($user);
    
        // Check if the cart session is not empty
        if (Session::has('cart') && !empty(Session::get('cart'))) {
            $cartItems = Session::get('cart');
    
            foreach ($cartItems as $item) {
                Carts::create([
                    'user_id' => $user->id,
                    'sku_id' => $item['id'],
                    'products_id' => $item['p_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
    
            // Clear the session cart after storing in database
            Session::forget('cart');
        }
    
        return redirect()->route('shop');
    }
    

    public function checkUser()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
    }

    public function mount()
    {
        $this->checkUser();
    }

    public function render()
    {
        return view('livewire.pages.login')->layout('components.layouts.app')->title('Login');
    }
}