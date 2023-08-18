<?php

namespace App\Http\Controllers;

use App\Events\EmailVerificationProcessed;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('no-auth');
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserRegisterRequest $userRegisterRequest)
    {
        $validated_data = $userRegisterRequest->validated();
        $picture = $userRegisterRequest->file('picture');
        $url = $picture->hashName();
        DB::transaction(function () use ($validated_data, $url) {
            $user = User::create([
                'first_name' => $validated_data['first_name'],
                'last_name' => $validated_data['last_name'],
                'username' => $validated_data['username'],
                'email' => $validated_data['email'],
                'password' => Hash::make($validated_data['password']),
                'ticket' => Str::random(100),
            ]);
            $user->image()->create([
                'url' => $url
            ]);
            EmailVerificationProcessed::dispatch(User::findOrFail($user->id));
        });
        $userRegisterRequest->file('picture')->storeAs('profile', $url, 'public');
        session()->flash('success', 'Done, you need to verify email address, go check your email');
        return redirect()->route('login.view'); //jangan ke home karena nanti auto login jadinya
    }
}
