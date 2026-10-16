<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controller;


class UserController extends Controller
{
    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->pass),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User added successfully');
    }
}
