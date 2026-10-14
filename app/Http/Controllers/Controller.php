<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controller;


class UserController extends Controller{
    public function user(Request $request){
        User::create([
            'Name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->pass),
            'role' => $request->role,
        ]);
    }
}