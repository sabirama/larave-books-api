<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request, $all) {
        $pageSize = $request->page_size ? $request->page_size : 50;
        $pageNumber = $request->page_on ? $request->page_on : 1;

        if ($all == 'all') {
            try {
                $users = User::with('userDetails');

                if ($users->count() === 0) {
                    return response()->json(['message' => 'No users yet.'], 404);
                }

                $users = $users->paginate($pageSize, ['*'], 'page', $pageNumber);


                $currentPage = $pageNumber;
                $totalPages = $users->lastPage();

                return response()->json(['data' => UserResource::collection($users), 'current_page' => $currentPage, 'total_page' => $totalPages ], 200);

            } catch (\Exception $e) {
                return response()->json(['error' => 'Server Error'], 500);
            }
        }

        else if (is_numeric($all)) {
            try {
                $user = User::find($all)->load('userDetails');

                if (!$user) {
                    return response()->json(['message' => 'User not found.'], 404);
                }

                return response()->json(['data' => new USerResource($user)], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Server Error'], 500);
            }
        }

        else {
            return response()->json(['message' => 'Not a valid Input'], 401);
        }
    }

   public function userDetails(Request $request, $id) {
        try {
            $user = UserDetails::where('user_id', $id)->first();

            if (!$user) {
                $createUserDetails = UserDetails::create([
                    'user_id' => $id,
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'address' => $request->input('address'),
                ]);

                return response()->json(['data' => $createUserDetails],200);
            }

            else if ($request->hasFile('image')){
                $validImage = $request->validate(['image' => 'mimes:jpeg,png,jpg']);

                if ($validImage) {

                         if (Storage::exists('/public/user/'.$id)) {
                             Storage::deleteDirectory('/public/user/'.$id);
                         }

                         $file = $request->file('image');
                         $filename = 'user-' . $id . "-profile-" . date('M-D-Y') . time() . '.' .$file->getClientOriginalExtension();
                         $file->storeAs('public/user/'.$id.'/',$filename);
                         $filepath = '/storage/user/'.$id.'/'.$filename;

                         $user->update([
                            $request->all(),
                            'profile_image' => $filepath]);
                         return response()->json(['message' => 'Profile image added to user.'], 200);
                }

                else {
                    return response()->json(['message' => 'Error updating user.'], 401);
                }
        }
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
