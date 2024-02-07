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
    public function index(Request $request)
    {
        $pageSize = $request->page_size ? $request->page_size : 50;
        $pageNumber = $request->page_on ? $request->page_on : 1;

        try {
            $booksQuery = Book::query();

            if ($request->search) {
                $searchQuery = $request->search;
                $booksQuery->where('title', 'like', '%' . $searchQuery . '%');
            }

            $books = $booksQuery->paginate($pageSize, ['*'], 'page', $pageNumber);
            $books->load('author', 'genre', 'rating');

            $currentPage = $pageNumber;

            $totalPages = $books->lastPage();

            return response()->json([BookResource::collection($books), 'current_page' => $currentPage, 'total_pages' => $totalPages], 200);
        } catch (\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }

    public function show($id)
    {

        if ($id === 'genres') {
            return response()->json([Genre::get('name')],200);
        }

        if ($id === 'authors') {
            return response()->json([Author::get('name')],200);
        }
        try {
            $book = Book::with('author', 'genre', 'rating')->find($id);

            if (!$book) {
                return response()->json(['message' => 'Book does not exist!'], 404);
            }
            return response()->json([new BookResource($book)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error.'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $book = Book::create($request->all());

            if ($request->input('author')) {
                //if there is author in request data
                $author = $this->createOrFindAuthor($request->input('author'));
                BookAuthors::create([
                    'book_id' => $book->id,
                    'author_id' => $author->id
                ]);
            }

            if ($request->input('genre')) {
                //if there is genre in request data
                $genre = $this->createOrFindGenre($request->input('genre'));
                BookGenres::create([
                    'book_id' => $book->id,
                    'genre_id' => $genre->id
                ]);
            }

            return response()->json([new BookResource($book), 'message' => 'Book created!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $book = Book::find($id);
            if (!$book) {
                return response()->json(['message' => 'Book not found.'], 404);
            }

            $book->update($request->all());

            if ($request->input('author')) {
                $author = $this->createOrFindAuthor($request->input('author'));
                BookAuthors::firstOrCreate(['author_id' => $author->id, 'book_id' => $book->id]);
            }

            if ($request->input('genre')) {
                $genre = $this->createOrFindGenre($request->input('genre'));
                BookGenres::firstOrCreate(['genre_id' => $genre->id, 'book_id' => $book->id]);
            }

            return response()->json([new BookResource($book), 'message' => 'Book updated!'], 200);
        } catch (\Exception $e) {
            return response()->json(['Server Error.'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            $book = Book::find($id);
            if (!$book) {
                if ($id === 'authors') {
                    $author = Author::where('name', $request->input('author'))->first();
                    if (!$author) {
                        return response()->json(['message' => 'Author does not exist.'], 404);
                    }
                    $author->delete();
                    return response()->json(['message' => 'Author deleted.'], 200);
                }

                if ($id === 'genres') {
                    $genre = Genre::where('name', $request->input('genre'))->first();
                    if (!$genre){
                        return response()->json(['message' => 'Genre does not exist.'], 200);
                    }
                    $genre->delete();
                    return response()->json(['message' => 'Genre deleted'], 200);
                }
                return response()->json(['message' => 'book not found.'], 404);
            }

            $request->author ? BookAuthors::where('book_id', $id)->delete() : null;
            $request->genre ? BookGenres::where('book_id', $id)->delete() : null;
            $book->delete();
            return response()->json(['data' => new BookResource($book), 'message' => 'Book removed!'], 200);

        } catch (\Exception $e) {
            return response()->json(['Server Error'], 500);
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

