<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\BookAuthors;
use App\Models\BookGenres;
use App\Http\Resources\BookResource;

use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {
        $book = Book::with('author', 'genre')->get();
        return response()->json(['data' => BookResource::collection($book)],200);
    }

    public function show($id) {
        try {
            $book = Book::with('author', 'genre')->find($id);

            if (!$book) {
                return response()->json(['data' => 'Book does not exist!'], 200);
            }

            return response()->json(['data' => new BookResource($book)],200);
        }
        catch (Exceptions $e) {
            return response()->json(['error' => 'Server Error.'],500);
        }
    }

    public function create(Request $request)
    {
        try {
            $book =  $book = Book::create($request->all());

            $author = $this->createOrFindAuthor($request->input('author'));
            $genre = $this->createOrFindGenre($request->input('genre'));

            //if there is author in request data
            $request->input('author') ?
            $bookAuthors = BookAuthors::create([
                'book_id'=> $book->id,
                'author_id'=>$author->id
            ]) : $bookAuthors = null;

            //if there is genre in request data
            $request->input('genre') ? $bookGenres = BookGenres::create([
                'book_id'=> $book->id,
                'genre_id'=>$genre->id
            ]) : $bookGenres = null;

            $book = [
                'title' => $book->title,
                'details' => $book->details,
                'price' => $book->price,
                'author' => $author,
                'genre' => $genre,

            ];

            return response()->json(['data'=> $book, 'message' => 'Book created!'], 200);
        } catch (\Exceptions $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
                $book = Book::find($id);
                if (!$book) {
                    return response()->json(['message' => 'book not found.'],404);
                }

                $book->update($request->all());
                $author = $this->createOrFindAuthor($request->input('author'));
                $genre = $this->createOrFindGenre($request->input('genre'));

                $request->author ? $bookAuthors = BookAuthors::create(['author_id' => $author->id, 'book_id' => $book->id]) : $bookAuthors = null;
                $request->genre ?  $bookGenres = BookGenres::create(['genre_id' => $genre->id, 'book_id'=> $book->id]) : $bookGenres = null;

                $book = [
                    'title' => $book->title,
                    'details' => $book->details,
                    'price' => $book->price,
                    'author' => $author->get(['id','name']),
                    'genre' => $genre,

                ];

                return response()->json(['data' => $book, 'message' => 'Book updated!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    public function destroy(Request $request, $id) {

        try {
            $book = Book::find($id);
           if (!$book) {
                return response()->json(['message' => 'book not found.'],404);
           }
           $request->author ? $bookAuthors = BookAuthors::where('book_id', $id)->delete() : null;
           $request->genre ? $bookGenre = BookGenres::where('book_id', $id)->delete() : null;
           $book->delete();
           return response()->json(['data' => $book, 'message' => 'Book removed!'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    private function createOrFindAuthor($authorData)
    {
        return Author::firstOrCreate(['name' => $authorData]);
    }

    private function createOrFindGenre($genreData)
    {
        return Genre::firstOrCreate(['name' => $genreData]);
    }
}

