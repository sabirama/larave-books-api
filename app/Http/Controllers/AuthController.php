<?php


namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authUser(Request $request, $action) {
        try {
            $request->validate([
                'username' => 'string|required|min:8',
                'password' => 'string' . ($action == 'logout' ? '' : '|required'),
                'email' => 'email' . ($action == 'register' ? '|required' : ''),
            ]);

            $user = null;
            $token = null;

            if ($action == 'register') {
                try {
                    $user = User::create($request->all());
                    $token = $user->createToken('login-token')->plainTextToken;
                }  catch (\Exception $e) {
                    return response()->json(['error'=>'Database Error']);
                }
            }

            if ($action == 'login') {
                try {
                    $user = User::where('username', $request->username)->first();

                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json(['error' => 'Invalid credentials'], 401);
                }
                $token = $user->createToken('login-token')->plainTextToken;
                } catch (\Exception $e) {
                    return response()->json(['error'=> 'Database Error']);
                }
            }

            return response()->json(['user' => $user, 'token' => $token]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request) {
            try {
                $user = User::where('username', $request->username)->first();

            if ($user) {
                $user->tokens()->where('name', 'login-token')->delete();
                return response()->json(['message' => 'Logout successful']);
            } else {
                return response()->json(['error' => 'Invalid user'], 401);
            }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Database Error'], 500);
            }
    }
}
