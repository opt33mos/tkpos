<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {
        return User::latest()->paginate(10);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users',
            'role' => 'required|string|max:191',
            'password' => 'required|string|min:6',
            'confirm_password' => ['same:password']
        ]);

        return User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'role' => $request['role'],
            'password' => Hash::make($request['password']),
        ]);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users,username,' . $user->id,
            'role' => 'required|string|max:191',
            'password' => 'required|string|min:6',
            'confirm_password' => ['same:password']
        ]);

        $user->update($request->all());
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
