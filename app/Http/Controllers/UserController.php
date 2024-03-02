<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request)
    {
        try {
            return response()->json([UserResource::collection(User::all())], 200);
        } catch (\Exception $e) {

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }
            $user->load('userDetails');
            return response()->json([new UserResource($user)], 200);
        } catch (\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }
    public function create(Request $request, $id)
    {
        try {
            $user = UserDetails::where('user_id', $id)->first();

            if (!$user) {
                $createUserDetails = UserDetails::create([
                    'user_id' => $id,
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'address' => $request->input('address'),
                    'profile_image' => $request->input('profile_image')
                ]);

                return response()->json([$createUserDetails], 200);
            } else {
                return response()->json(['message' => 'Error updating user.'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['Server Error'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $userDetails = UserDetails::where('user_id', $id)?->first();

                $userDetails->update($request->all());
                return response()->json([$userDetails], 200);

            } else {
                return response()->json(['message' => 'User does not exist. Please create a user or make sure to input the correct user Id.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }
}
