<?php

namespace App\Http\Controllers;

use App\Http\Requests\userStoreRequest;
use App\Models\User;

class UsersController extends Controller
{
    public function store(userStoreRequest $request){
        if (auth()->check() && auth()->user()->role == 'admin') {

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->role = $request->input('role');
            $user->save();

            return response()->json(['message' => 'done']);
        }
        return response()->json(['message' => 'not available for you'], 403);
    }
}
