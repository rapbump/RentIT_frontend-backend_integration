<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $role = Auth::user()->role;

            if ($role == 'user'){
                return redirect()->route('customer.browse');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }
}

