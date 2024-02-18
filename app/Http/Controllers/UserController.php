<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TaskController;

class UserController extends Controller
{
    public function showLoginForm()
    {
        if (auth()->check()) {
            // User is already logged in, redirect them to the dashboard or another appropriate page
            return redirect()->route('tasks.list');
        }
    
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            //return redirect()->intended('dashboard');
            return redirect()->route('tasks.list');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'user_role' => 'required',
            'password' => 'required|min:4|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_role' => $request->user_role,
            'password' => Hash::make($request->password),
        ]);

         // Log in the user
        Auth::login($user);

        return redirect()->route('tasks.list')->with('success', 'User registered successfully.');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('tasks.list');
    }
}
