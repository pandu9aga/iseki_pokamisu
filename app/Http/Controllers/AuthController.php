<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('user_id')) {
            return redirect()->route('data.index');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && $user->password === $request->password) {
            Session::put('user_id', $user->id);
            Session::put('username', $user->username);
            return redirect()->route('data.index');
        }

        return back()->withErrors(['login' => 'Username atau password salah']);
    }

    public function logout()
    {
        Session::forget('user_id');
        Session::forget('username');
        return redirect()->route('login');
    }
}
