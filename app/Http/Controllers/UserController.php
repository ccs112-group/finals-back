<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Method for user login
    function login(Request $req)
    {
        $user = User::where(['email' => $req->email])->first();
        if ($user && Hash::check($req->password, $user->password)) 
        {
            return response()->json($user);
        }
        else
        {
            return response([
                'error' => ['Email or password not match']
            ]);
        }
    }

    // Method for user registration
    function register(Request $req)
    {
        $validated = $req->validate([
            'name' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|alpha_num|min:6',
            'confirm_password' => 'required|same:password',
        ], 
        [
            'confirm_password.same' => 'Confirm password not match.',
        ]);

        $user = new User;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->save();

        return response()->json($user);
    }

    // Method to get all users
    public function index()
    {
        return User::all();
    }

    // Method to update a user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user -> update($request->all());
        return response()->json($user,200);
    }

    // Method to delete a user
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
