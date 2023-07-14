<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserLoginRequest $userLoginRequest)
    {
        $userLoginRequest->validated();
        $credentials = $userLoginRequest->only(['username', 'password']);
        if(Auth::attempt($credentials, $userLoginRequest->has('remember')))
        {
            $userLoginRequest->session()->regenerate();
            session()->flash('success', 'Wellcome '.Auth::user()->username);
            return redirect()->route('home');
        }
        session()->flash('error', 'Username or password is wrong');
        return redirect()->route('login.view');
    }
}
