<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin' || $user->role === 'staff') {
                return redirect()->route('dashboard');
            }

            return redirect()->route('resident.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | LOGIN VALIDATION
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'login')
                ->withInput()
                ->with('open_modal', 'login');
        }

        $credentials = $validator->validated();


        /*
        |--------------------------------------------------------------------------
        | CHECK EMAIL FIRST
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'We could not find an account with this email address.',
                ], 'login')
                ->withInput()
                ->with('open_modal', 'login');
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!Auth::validate($credentials)) {
            return back()
                ->withErrors([
                    'password' => 'The password you entered is incorrect.',
                ], 'login')
                ->withInput()
                ->with('open_modal', 'login');
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN SUCCESSFULLY
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();

        if ($user->role === 'admin' || $user->role === 'staff') {
            return redirect()->intended('/dashboard');
        }

        return redirect()->intended('/resident/dashboard');
    }

    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | REGISTRATION VALIDATION
        |--------------------------------------------------------------------------
        | Registration errors go into the separate "register" error bag.
        */

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'min:8',
            ],

            'password_confirmation' => [
                'required',
                'same:password',
            ],
        ], [
            'password_confirmation.same' =>
                'The password confirmation does not match the password.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | REGISTRATION FAILED
        |--------------------------------------------------------------------------
        | Tell the login page to automatically reopen the Register modal.
        */

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'register')
                ->withInput()
                ->with('open_modal', 'register');
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $data = $validator->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'resident',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AUTOMATIC LOGIN AFTER REGISTRATION
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
        ->route('resident.dashboard')
        ->with('success', 'Account successfully registered! Welcome to e-REKLAMO.');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}