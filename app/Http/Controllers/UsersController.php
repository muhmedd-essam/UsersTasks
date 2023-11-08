<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginUsersRequest;
use App\Http\Requests\registerUsersRequest;
use App\Http\Requests\userStoreRequest;
use App\Http\Requests\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller {
    public function register(registerUsersRequest $request){
        if (auth()->check() && auth()->user()->role == 'admin') {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->role = $request->input('role');
            $user->save();

            return response()->json(['message' => 'done registration', 'data'=>$user]);
        }elseif(auth()->check() && auth()->user()->role == 'team_leader' && $request->input('role') == 'employee') {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->role = $request->input('role');
            $user->leader_id = auth()->user()->id;
            $user->save();
            return response()->json(['message' => 'done registration', 'data'=>$user]);
        }
        return response()->json(['message' => 'not available for you']);
    }

    public function login(loginUsersRequest $request){
        $token=JWTAuth::attempt(['email' => $request->email, 'password' => $request->password]);
        if(!empty($token)){
            $user =User::where('email', $request->email)->first();
            return response()->json(['message' => 'done', 'data'=>[$token, $user]]);
        }
    }
}
