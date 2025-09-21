<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller {
    public function showRegisterForm() {
        $divisions = ['HR','IT','Finance','Ops'];
        return view('auth.register',compact('divisions'));
    }

    public function register(Request $request) {
        $data = $request->validate([
            'username'=>'required',
            'divisi'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed|min:8',
            'profile_photo'=>'nullable|image|mimes:jpg,png|max:2048'
        ]);
        $path = $request->file('profile_photo')?->store('profile_photos','public');

        $user = User::create([
            'username'=>$data['username'],
            'divisi'=>$data['divisi'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'profile_photo'=>$path,
            'is_admin'=>false,
        ]);

        Auth::login($user);
        return redirect()->route('user.dashboard');
    }

    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if (Auth::attempt(array_merge($credentials,['is_admin'=>false]))) {
            $request->session()->regenerate();
            return redirect()->route('user.dashboard');
        }
        return back()->withErrors(['email'=>'Email atau password salah']);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
