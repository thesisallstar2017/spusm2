<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function form()
    {
        return view('change-password.form');
    }

    public function change(ChangePasswordRequest $request)
    {
        $credentials = [
            'email' => Auth::user()->email,
            'password' => $request->input('current_password'),
        ];

        if (! Auth::once($credentials)) {
            return back()->withErrors([
                'current_password' => 'Incorrect Current Password',
            ]);
        }

        $user = Auth::user();
        $user->password = bcrypt($request->input('password'));
        $user->save();

        alert()->success('Passsword changed');

        return redirect('/');
    }
}
