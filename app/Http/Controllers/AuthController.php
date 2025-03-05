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
        // Validasi input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
    
        // Simpan user dengan password yang di-hash
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    
        // Return response JSON dengan status 201 (Created)
        return response()->json([
            'message' => 'Registered successfully',
            'redirect' => '/login',
        ], 201);
    }
    

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
