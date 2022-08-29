<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetConfirmationMessage;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    /**
     * 
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function sendEmailToken()
    {
    	$rules = [
            'email'         =>   ['required', 'email', 'max:255']
        ];

        $this->validate(request(), $rules);

        User::where('email', request('email'))->firstOrFail();

        PasswordReset::where( 'email', request('email') )->delete();

        $fields = request()->all();
        $fields['token'] = PasswordReset::generateVerificationToken();
        $fields['expires_at'] = now()->addHours(1);
        $passwordReset = PasswordReset::create($fields);
        return response()->json([ 'data' => $passwordReset ], 200);
    }

    /**
     * 
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword($token)
    {
        $rules = [
            'email'         =>   ['required', 'email', 'max:255'],
            'password'      =>   ['required', 'string', 'min:8', 'confirmed']
        ];
        $this->validate(request(), $rules);
        $emailToken = PasswordReset::where([
                                        'email' => request('email'),
                                        'token' => request('token'),
                                    ])
                                    ->firstOrFail();
        if (now()->format('Y-m-d H:i:s') > $emailToken->expires_at) {
            $emailToken->delete();
            $fields = request()->all();
            $fields['token'] = PasswordReset::generateVerificationToken();
            $fields['expires_at'] = now()->addHours(1);
            $passwordReset = PasswordReset::create($fields);
            return $this->errorResponse('Expired token, token forwarded', 400);
        }
        $user = User::where('email', request('email'))->firstOrFail();
        $user->password = request('password');
        $user->save();
        $emailToken->delete();
        Mail::to($user)->queue(new PasswordResetConfirmationMessage($user));
        return response()->json([ 'data' => $user ], 200);
    }
}
