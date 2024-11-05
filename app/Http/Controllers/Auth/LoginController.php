<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('movies.index');
        }
        return back()->withErrors(['message' => 'Username atau Password salah']);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
