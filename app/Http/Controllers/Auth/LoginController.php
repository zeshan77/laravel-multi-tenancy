<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{

    // Add this function. This will redirect users to corresponding URL 
    // after successfully logged in
    protected function redirectTo()
    {

        if (isTenant()) return route('tenant.dashboard', ['subdomain' => tenant()->domain]);

        return route('dashboard');
    }
}
