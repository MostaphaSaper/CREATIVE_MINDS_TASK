<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
        $users = User::latest()->get();

        return $users;
    }

    public function store(Request $requset){
        $requset->validate([
            'phone' => 'required|unique:users,phone'
        ]);
        return User::create([
            'name' => $requset->name,
            'email' => $requset->email,
            'phone' => $requset->phone,
            'password' => bcrypt($requset->password),
        ]);
    }

    public function update(User $user,Request $requset){
        $requset->validate([
            'phone' => 'required|unique:users,phone,'.$user->id,
        ]);
        $user->update([
            'name' => $requset->name,
            'phone' => $requset->phone,
            'email' => $requset->email,
            'password' => bcrypt($requset->password),
        ]);
        return $user;
    }

    public function destroy(User $user){
        $user->delete();
        return response()->noContent();
    }
}
