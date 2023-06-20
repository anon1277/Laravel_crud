<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function view_register_page() {
        return view('register');
     }

   public function register(Request $request)
   {
       $request->validate([
           'first_name' => 'required|string',
           'last_name' => 'required|string',
           'email' => 'required|email|unique:users,email',
           'password' => 'required|string|min:8|confirmed',
       ]);

       $user = User::create([
           'first_name' => $request->input('first_name'),
           'last_name' => $request->input('last_name'),
           'email' => $request->input('email'),
           'password' => Hash::make($request->input('password')),
       ]);

       return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
   }


    //method to display a user login page

    public function view_login_page() {
        return view('login');
     }

     //method login action

     public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    //method to logout
    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }



}
