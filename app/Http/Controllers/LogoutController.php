<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom-auth');
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if(Auth::check())
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            session()->flash('success', 'Done');
            return redirect()->route('home');
        }
        session()->flash('error', 'You are not logged in');
        return redirect()->route('login.view');
    }
}
