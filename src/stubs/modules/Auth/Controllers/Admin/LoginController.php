<?php

namespace App\Modules\Auth\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('vendor.section.auth.login');
    }

    public function login()
    {
        $credentials = request()->only(['email', 'password']);
        $login = auth()->attempt($credentials);
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
