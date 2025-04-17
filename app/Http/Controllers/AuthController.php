<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Rules\ReCaptcha;

class AuthController extends Controller
{

    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        // Validasi input, termasuk reCAPTCHA
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            // 'g-recaptcha-response' => ['required', new ReCaptcha]  // Validasi reCAPTCHA
        ]);

        // Periksa apakah validasi gagal
        if ($validator->fails()) {
            // Cek apakah kegagalan disebabkan oleh reCAPTCHA
            if ($validator->errors()->has('g-recaptcha-response')) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('recaptchaError', 'Please complete the CAPTCHA before submitting.');
            }

            // Jika kesalahan bukan pada reCAPTCHA, kembali dengan pesan error lainnya
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek apakah user bisa login
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Simpan pesan kesalahan dalam session
            return redirect()->back()->with('error', 'Username or Password is incorrect.');
        }

        // Jika login berhasil, regenerasi session dan redirect ke dashboard
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }


    public function showLoginForm()
    {
        return view('auth/login');
    }
}
