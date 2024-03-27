<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed'
            ]);

            $existingUser = User::where('email', $fields['email'])->first();
            if ($existingUser) {
                return response()->json(['error' => 'User already exists'], 409);
            }

            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => Hash::make($fields['password']),
            ]);

            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response($response, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
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
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (! $user || ! Hash::check($fields['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        return response()->json(['token' => $token, 'message' => 'Successfully logged in'], 200);
    }



}

