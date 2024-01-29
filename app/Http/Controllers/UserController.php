<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;

use Illuminate\Http\Request;

class UserController extends Controller
{

   public function userDetails(Request $request, $id) {
        try {
            $user = User::find($id)->first();

        if ($user) {
            $userDetails = UserDetails::create([
                'user_id' => $id,
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'address' => $request->input('address'),
            ]);

            return response()->json([$userDetails],200);
        }
        }
        catch (\Exceptions $e) {
            return response()->json(['error' => $e],400);
        }
   }

   public function updateUserDetails(Request $request, $id) {
        try {
            $user = User::find($id)->first();

        if ($user) {
            $userDetails = UserDetails::update($request->all());

            return response()->json([$userDetails],200);
        }
        }
        catch (\Exceptions $e) {
            return response()->json(['error' => $e],400);
        }

    }
}
