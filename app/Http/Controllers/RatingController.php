<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Http\Resources\RatingResource;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index() {
        try {
            $rate = Rating::with('book', 'user')->get();
            return response()->json(['rate' => RatingResource::collection($rate)], 200);
        }
        catch (\Exceptions $e) {
            return response()->json(['error' => 'Server Error.'], 500);
        }
    }

    public function show($id) {

       try {
            $rate = Rating::with('book', 'user')->where('book_id', $id)->get();
            if ($rate->count() === 0) {
                return response()->json(['message' => 'No Rating for this book yet. Be the first to rate it!'], 201);
            }
            return response()->json(['data' => RatingResource::collection($rate)], 200);
       }
       catch (\Exception $e) {
        return response()->json(['error' => 'Server Error.'], 500);
       }
    }

    public function create(Request $request) {
        try {
            return response()->json(['rate' => Rating::create($request->all()),'message' => 'Rate created!'], 200);
        }
        catch(\Exception $e) {
            return response()->json(['error' => 'Server Error.'], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $rate = Rating::find($id);

            if(!$rate) {
                return response()->json(['message' => 'Rate not found!'], 201);
            }

            $rate->update($request->all());
            return response()->json(['rate' => $rate, 'message' => 'Rate updated!'], 200);
        }
        catch(\Exception $e) {
            return response()->json(['error' => 'Server Error.'], 500);
        }
    }

    public function destroy(Request $request, $id) {
        try {
            $rate = Rating::find($id);
            if (!$rate) {
                return response()->json(['message' => 'Rate not found!'], 201);
            }
            $rate->delete();
            return response()->json(['rate' => $rate, 'message' => 'Rate deleted!'], 200);
        }
        catch(\Exception $e) {
            return response()->json(['error' => 'Server Error.'], 500);
        }
    }
}
