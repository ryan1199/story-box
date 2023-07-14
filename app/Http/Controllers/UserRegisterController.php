<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserRegisterRequest $userRegisterRequest)
    {
        $validated_data = $userRegisterRequest->validated();
        $picture = $userRegisterRequest->file('picture');
        $url = $picture->hashName();
        $userRegisterRequest->file('picture')->storeAs('profile', $url, 'public');
        $user = User::create([
            'first_name' => $validated_data['first_name'],
            'last_name' => $validated_data['last_name'],
            'username' => $validated_data['username'],
            'email' => $validated_data['email'],
            'password' => Hash::make($validated_data['password'])
        ]);
        $user->image()->create([
            'url' => $url
        ]);
        session()->flash('success', 'Done');
        return redirect()->route('home');
    }
}
