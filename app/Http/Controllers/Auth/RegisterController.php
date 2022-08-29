<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMessage;
use App\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * 
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $rules = [
            'name'          =>   ['required', 'string', 'max:255'],
            'surnames'      =>   ['required', 'string', 'max:255'],
            'email'         =>   ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      =>   ['required', 'string', 'min:8', 'confirmed']
        ];
        $this->validate(request(), $rules);
        $fields = request()->all();
        $fields['verification_token'] = User::generateVerificationToken();
        $user = User::create($fields);
        $user->assignRole('free');
        return response()->json([ 'data' => $user ], 200);
    }
}
