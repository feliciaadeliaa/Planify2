<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);
        return Inertia::render('profile ', compact('user'));
    }
}
