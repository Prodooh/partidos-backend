<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        // $this->middleware( 'can:get-all-users' )->only( 'index' );
        // $this->middleware( 'can:create-users' )->only( 'store' );
        // $this->middleware( 'can:update-users' )->only( 'update' );
        // $this->middleware( 'can:delete-users' )->only( 'delete' );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->load('roles');
        return $this->showOne($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'          =>   ['required', 'string', 'max:255'],
            'surnames'      =>   ['required', 'string', 'max:255'],
            'email'         =>   ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'         =>   ['unique:users'],
            'password'      =>   ['required', 'string', 'min:4'],
            'role'          =>   ['required', 'string']
        ];
        $this->validate(request(), $rules);
        $user = User::create(request()->all());
        $user->assignRole(request( 'role' ));
        return response()->json([ 'data' => $user ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name'          =>   ['string', 'max:255'],
            'surnames'      =>   ['string', 'max:255'],
            'email'         =>   ['string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone'         =>   ['unique:users,phone,'.$user->id],
            'password'      =>   ['string', 'min:4'],
            'role'          =>   ['string']
        ];

        $this->validate(request(), $rules);

        $user->fill(request()->only(
            'name', 'surnames', 'password'
        ));

        if (request('email') && $user->email != request('email')) {
            $user->email = request('email');
        }

        if (request('phone') && $user->phone != request('phone')) {
            $user->phone = request('phone');
        }

        if (request('role')) {
            $user->syncRoles([request('role')]);
        } else {
            $user->removeRole($user->getRoleNames()[0]);
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->removeRole($user->getRoleNames()[0]);
        $user->delete();
        return $this->showOne($user);
    }

    /**
     * Obtener información del usuario logeado
        ROLE
        ** TODOS LOS ROLES
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function me() {
        $userAuthenticated = [
            'id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'surnames' => auth()->user()->surnames,
            'email' => auth()->user()->email,
            'role' => auth()->user()->getRoleNames()[0],
            'sizes' => auth()->user()->sizes
        ];
        return $this->showOne($userAuthenticated);
    }

    /**
     * Cerrar sesión
        ROLE
        ** TODOS LOS ROLES
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        request()->user()->token()->revoke();
        return $this->showMessage('Logout', 200);
    }
}
