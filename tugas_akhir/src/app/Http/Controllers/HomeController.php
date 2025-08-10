<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // cek apakah user sudah login
        if (auth()->check()) {
            // jika sudah login, redirect ke dashboard sesuai role
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (auth()->user()->role === 'customer') {
                return redirect()->route('customer.dashboard');
            } elseif (auth()->user()->role === 'owner') {
                return redirect()->route('owner.dashboard');
            }
            // jika tidak ada role yang cocok, redirect ke halaman utama
            return redirect()->route('home');
        }
    }
}
