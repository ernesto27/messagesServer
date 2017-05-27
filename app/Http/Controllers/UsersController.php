<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;

class UsersController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        // validate request
        $validator = \Validator::make($request->all(), ['name' => 'required', 'email' => 'required', 'password' => 'required']);
        if ($validator->fails()) {
            $response = [
                'status' => 'error',
                'errors' => [$validator->errors()]
            ];
            return response()->json($response, 422);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);

        if($user){
            return response()->json(['status' => 'ok']);
        }
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if($user){
            if(password_verify($request->input('password'), $user->password)){
                return response()->json(['status' => 'ok', 'message' => 'User logged']);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
    }



}
