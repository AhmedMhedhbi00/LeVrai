<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Bentornato!');
        }

        return back()->withErrors([
            'email' => 'Email o password non corretti.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname'  => 'required|string|max:100',
            'username'  => 'required|string|min:3|max:50|unique:users,name',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'      => $request->username,
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Benvenuto in LeVrai, ' . $request->firstname . '!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}