<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'User');
        Session::put('menu', 'User');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        if (request()->ajax()) {
            return $users;
        }

        $levels = ['Sales', 'Manager'];
        return view('administration.user.index', compact('users', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:users'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'level' => ['required', 'in:Sales,Manager'],
            'password' => ['required', 'confirmed', 'min:5'],
        ]);

        User::create($validated);
        return Response()->json([
            'content' => 'user ' . $validated['name'] . ' added!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return Response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('users', 'name')->ignore($id)
            ],
            'email' => [
                'required',
                Rule::unique('users', 'email')->ignore($id)
            ],
            'level' => ['required', 'in:Sales,Manager']
        ]);

        $user = User::find($id);
        $user->update($validated);

        return Response()->json([
            'content' => 'user updated!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        return Response()->json([
            'content' => 'user ' . $user['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
