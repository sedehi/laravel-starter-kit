<?php

namespace App\Http\Controllers\Auth\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('vendor.section.auth.login');
    }

    public function login()
    {
        $credentials = request()->only(['email', 'password']);
        $login       = auth()->attempt($credentials);
        if ($login) {
            request()->session()->regenerate();
            if (request()->has('return')) {
                return redirect()->to(request('return'));
            }

            return redirect()->action([AdminController::class, 'index']);
        }

        return back()->withInput()->withErrors([
            'error' => trans('auth.failed'),
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->action([LoginController::class, 'showLoginForm']);
    }
}
