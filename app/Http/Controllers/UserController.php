<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255|unique:users',
            'password'          => 'required|min:8',
            'confirm_password'  => 'required|same:password',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
        //if validation success
        $password = bcrypt($request->password);
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $password,
        ]);
        $data['token']  =   $user->createToken('userToken')->plainTextToken;
        $data['name']   =   $user->name;
        $data['email']  =   $user->email;

        return $this->sendResponse($data, 'User register successfully');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email|max:255|',
            'password'  => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $data['token'] = $user->createToken('RestApi')->plainTextToken;
            $data['email'] = $user->email;
            return $this->sendResponse($data, 'User logged in successfully');
        } else {
            return $this->sendError('Unauthorized', ['error' => 'Unauthorized']);
        }
    }
    public function logout()
    {
        if (auth()->check()) {
            auth()
                ->user()
                ->tokens()
                ->delete();
            return $this->sendResponse([], 'Logged out');
        } else {
            return $this->sendResponse([], 'You are not logged in.');
        }
    }
}
