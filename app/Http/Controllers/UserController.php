<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{

   public function userDetails(Request $request, $id) {
        try {
            $user = User::find($id)->first();

            if ($user) {

                $userDetails = userDetails::where('user_id', $id)->first();

                if ($userDetails) {
                   $validImage = $request->validate(['image' => 'mimes:jpeg,png,jpg']);

                    if($request->hasFile('image')){

                        if (Storage::exists('/public/user/'.$id)) {
                            Storage::deleteDirectory('/public/user/'.$id);
                        }

                        $file = $request->file('image');
                        $filename = 'user-' . $id . "-profile-" . date('M-D-Y') . time() . '.' .$file->getClientOriginalExtension();
                        $file->storeAs('public/user/'.$id.'/',$filename);
                        $filepath = '/storage/user/'.$id.'/'.$filename;
                    }

                    $validImage ? $image = $userDetails->update(['profile_image' => $filepath]) : $image = 'not a valid image';
                }

                if(!$userDetails && ( $request->input('first_name') || $request->input('last_name') || $request->input('address') )) {
                    ($request->input('image') != null) ? $profile = $request->input('image')
                    : $profile = null;

                    $createUserDetails = UserDetails::create([
                        'user_id' => $id,
                        'first_name' => $request->input('first_name'),
                        'last_name' => $request->input('last_name'),
                        'address' => $request->input('address'),
                        'profile_image' =>$filepath
                    ]);
                }


                $image ? $image = ['image_path' => $userDetails->profile_image] : null;
                $createUserDetails ?? $message = ['message' => 'User details already exist! You might want to update user details.'];

                return response()->json([$message , $image], 201);
            }
            else {
                return response()->json(['message' => 'User does not exist! Please create user or input the correct user Id.'], 201);
            }

            return response()->json([$createUserDetails],200);

        }

        catch (\Exceptions $e) {
            return response()->json(['error' => 'Server Error'],500);
        }
   }

   public function updateUserDetails(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $userDetails = UserDetails::where('user_id', $id)?->first();

                $userDetails->update([
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'address' => $request->input('address'),
                ]);

                return response()->json(['data' => $userDetails], 200);

            } else {
                return response()->json(['message' => 'User does not exist. Please create a user or make sure to input the correct user Id.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error.'], 500);
        }
    }
}
