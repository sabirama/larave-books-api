<?php


namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authUser(Request $request, $action)
    {
        try {

            $validation = $request->validate([
                'username' => 'string|required|min:8',
                'password' => 'string' . ($action == ('login' | 'register') ? '|required' : ''),
                'email' => 'email' . ($action == 'register' ? '|required' : ''),
            ]);

            if ($action == 'login') {
                $user = User::where('username', $request->input('username'))->first();
                if (!$user || $request->input('password', $user->password)) {
                    return response()->json(['message' => 'Invalid username or password.'], 401);
                }
                $token = $user->createToken('user-token')->plainTextToken;
                return response()->json(['user' => $user, 'token' => $token], 200);

            } else if ($action == 'register') {
                $user = User::where('username', $request->input('username'))->first();
                if ($user) {
                    return response()->json(['message' => 'User already exist.'], 401);
                } else {
                    $createUser = User::create($request->all());
                    if (!$createUser) {
                        return response()->json(['message' => 'Registration failed.'], 401);
                    }
                    $token = $createUser->createToken('user-token')->plainTextToken;
                    return response()->json(['user' => $createUser, 'token' => $token], 200);
                }
            }

        } catch (\Exception $e) {
            return response()->json(['Server Error'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = User::where('username', $request->username)->first();

            if ($user) {
                $user->tokens()->where('name', 'user-token')->delete();
                return response()->json(['message' => 'Logout successful'], 200);
            } else {
                return response()->json(['message' => 'Invalid user'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['Server Error'], 500);
        }
    }
}
