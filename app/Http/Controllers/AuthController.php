<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Authenticated', 'redirect' => '/dashboard'], 200);
        } else {
            return response()->json(['message' => 'The provided credentials do not match our records.'], 401);
        }
    }

    public function register(Request $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        User::create($data);

        return response()->json(['message' => 'Authenticated', 'redirect' => '/login'], 200);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
