<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $existingUser = User::where('email', $request->input('email'))->first();
            if ($existingUser) {
                return response()->json(['error' => 'User already exists'], 409);
            }

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response($response, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed'], 500);
        }
    }

   /*  public function logout()
    {
        $user = auth()->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Successfully logged out']);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }
 */


 //logout groupe midd

/*  public function logout(Request $request)
 {
    $request->user()->currentAccessToken()->delete();

         return response()->json(['message' => 'Successfully logged out']);


 } */

 public function logout(Request $request) {
    auth()->user()->tokens()->delete();

    return [
        'message' => 'Logged out'
    ];
}

public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        throw ValidationException::withMessages($validator->errors());
    }

    $user = User::where('email', $request->get('email'))->first();

    if (!$user || !Hash::check($request->get('password'), optional($user)->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $token = $user->createToken('myapptoken', [
        'expires_in' => config('auth.token_lifetime'),
    ])->plainTextToken;

    return response()->json([
        'token' => $token,
        'message' => 'Successfully logged in',
    ], 200);
}


}

