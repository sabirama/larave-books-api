<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Http\Resources\RatingResource;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request) {
        $pageSize = $request->page_size ? $request->page_size : 50;
        $pageNumber = $request->page_on ? $request->page_on : 1;

        try {
            $rate = Rating::paginate($pageSize, ['*'], 'page', $pageNumber)
            ->load('book', 'user');
            return response()->json([RatingResource::collection($rate)], 200);
        }
        catch (\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }


    public function rate($id) {
        try {
            $rate = Rating::find($id);
            return response()->json([ new RatingResource($rate)],200);
        }
        catch(\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }
    public function show(Request $request, $id) {
        $pageSize = $request->page_size ? $request->page_size : 50;
        $pageNumber = $request->page_on ? $request->page_on : 1;

       try {
            $rate = Rating::where('book_id', $id)->paginate($pageSize, ['*'], 'page', $pageNumber)
            ->load('book', 'user');
            if ($rate->count() === 0) {
                return response()->json(['message' => 'No Rating for this book yet. Be the first to rate it!'], 201);
            }
            return response()->json([RatingResource::collection($rate)], 200);
       }
       catch (\Exception $e) {
        return response()->json(['Server Error.'], 500);
       }
    }

    public function create(Request $request) {
        try {
            return response()->json([Rating::create($request->all()),'message' => 'Rate created!'], 200);
        }
        catch(\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $rate = Rating::find($id);

            if(!$rate) {
                return response()->json(['message' => 'Rate not found!'], 404);
            }

            $rate->update($request->all());
            return response()->json([$rate, 'message' => 'Rate updated!'], 200);
        }
        catch(\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }

    public function destroy(Request $request, $id) {
        try {
            $rate = Rating::find($id);
            if (!$rate) {
                return response()->json(['message' => 'Rate not found!'], 404);
            }
            $rate->delete();
            return response()->json([$rate, 'message' => 'Rate deleted!'], 200);
        }
        catch(\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }
}
