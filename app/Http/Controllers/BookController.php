<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\BookAuthors;
use App\Models\BookGenres;
use App\Http\Resources\BookResource;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {
        try {
            $book = Book::with('author', 'genre')->get();
            return response()->json(['data' => BookResource::collection($book)],200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Server Error.'],500);
        }
    }

    public function show($id) {
        try {
            $book = Book::with('author', 'genre')->find($id);

            if (!$book) {
                return response()->json(['data' => 'Book does not exist!'], 201);
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

    public function addCover(Request $request, $id) {
        try {
            $validImage = $request->validate(['image' => 'mimes:jpeg,png,jpg']);

           if ($validImage) {

            $book = Book::find($id);
                if ($book) {
                    if (Storage::exists('/public/book/'.$id)) {
                        Storage::deleteDirectory('/public/book/'.$id);
                    }

                    $file = $request->file('image');
                    $filename = 'book-' . $id . "-cover-" . date('M-D-Y') . time() . '.' .$file->getClientOriginalExtension();
                    $file->storeAs('public/book/'.$id.'/',$filename);
                    $filepath = '/storage/book/'.$id.'/'.$filename;

                    $book->update([
                        'cover_image' => $filepath
                    ]);
                    return response()->json(['message' => 'Cover image added to book.'], 200);
                }
                return response()->json(['message' => 'Book does not exist'], 201);
           }

           return response()->json(['message' => 'Not a valid image. Supported files are jpg, jpeg and png.'], 201);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Server Error.'], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
                $book = Book::find($id);
                if (!$book) {
                    return response()->json(['message' => 'book not found.'],404);
                }

                if($request->input('title') || $request->input('details') || $request->input('price')) {
                    $book->update($request->all());
                }

                if ($request->author) {
                    $author = $this->createOrFindAuthor($request->input('author'));
                    $bookAuthors = BookAuthors::firstOrCreate(['author_id' => $author->id, 'book_id' => $book->id]);
                }

                if ($request->genre) {
                    $genre = $this->createOrFindGenre($request->input('genre'));
                    $bookGenres = BookGenres::firstOrCreate(['genre_id' => $genre->id, 'book_id'=> $book->id]);
                }

                return response()->json(['data' => new BookResource($book), 'message' => 'Book updated!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error.'], 500);
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

