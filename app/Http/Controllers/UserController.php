<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('user.index')->with([
            'users'  => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user  = User::find($id);
        $roles = Role::all();

        // if user not found OR
        // if not admin AND not editing own profile
        if (
            empty($user)
            || ($user->id != auth()->user()->id
                && auth()->user()->role->role != 'Admin')
        ) {
            return redirect()->route('dashboard');
        }

        return view('user.edit')->with([
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);

        if (empty($user)) {
            return redirect()->route('dashboard');
        }

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->role_id = $request->role;

        if (!empty($request->password) && !empty($request->confirm_password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        
        return redirect()->back()->with([
            'success' => 'Successfully updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
