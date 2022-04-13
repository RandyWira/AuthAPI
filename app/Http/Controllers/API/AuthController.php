<?php

namespace App\Http\Controllers\API;

use Auth;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            // return response()->json($validator->errors());
            return $this->handleError($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] =  $user->name;

        // return response()
        //     ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
        return $this->handleResponse($success, 'User successfully registered!');
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            // return response()
            //     ->json(['message' => 'Unauthorized'], 401);
            return $this->handleError('Unauthorised.', ['error'=>'Unauthorised']);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $auth = Auth::user();
        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] =  $auth->name;
        // return response()
        //     ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
        return $this->handleResponse($success, 'User logged-in!');

    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
