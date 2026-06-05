<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('index');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function login(Request $request)
    {
        try {


            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {

                $request->session()->regenerate();

                return response()->json([
                    'status' => 'success',
                  'redirect' => route('home')
                ], 200);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'Foutieve login gegevens.'
            ], 401);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Er ging iets mis met het verzoeken voor autorisatie...'
            ], 500);
            
        }
    }
}