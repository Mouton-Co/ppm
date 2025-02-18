<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->model = User::class;
        $this->route = 'user';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', User::class)) {
            abort(403);
        }

        $this->checkTableConfigurations('users', User::class);
        $users = $this->filter(User::class, User::query(), $request)->paginate(15);

        if ($users->currentPage() > 1 && $users->lastPage() < $users->currentPage()) {
            return redirect()->route('user.index', array_merge(['page' => $users->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Users',
            'table' => 'users',
            'route' => 'user',
            'indexRoute' => 'user.index',
            'data' => $users,
            'model' => User::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', User::class)) {
            abort(403);
        }

        $roles = Role::all();

        return view('user.create')->with([
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        if ($request->user()->cannot('create', User::class)) {
            abort(403);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role,
        ]);

        return redirect()->route('user.index')->with([
            'success' => 'User created',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (auth()->user()->cannot('update', User::class)) {
            abort(403);
        }

        $user = User::find($id);
        $roles = Role::all();

        return view('user.edit')->with([
            'user' => $user,
            'roles' => $roles,
            'edit' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        if (auth()->user()->cannot('update', User::class)) {
            abort(403);
        }

        $user = User::find($id);

        if (empty($user)) {
            return redirect()->route('dashboard');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role;

        if (! empty($request->password) && ! empty($request->confirm_password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with([
            'success' => 'User updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->user()->cannot('delete', User::class)) {
            abort(403);
        }

        $user = User::find($id);

        if (empty($user)) {
            return redirect()->route('dashboard');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('user.index')->with([
            'success' => "User $name has been removed",
        ]);
    }

    /**
     * Export users to excel
     *
     * @return \Maatwebsite\Excel\Excel
     */
    public function export(Request $request)
    {
        if ($request->user()->cannot('read', User::class)) {
            abort(403);
        }

        return (new UserExport($request))->download('users.xlsx');
    }
}
