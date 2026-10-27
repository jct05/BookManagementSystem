<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // GET: Display all students
    public function index(Request $request)
    {
        //optional search
        if ($request->has('search')) {
            $query = $request->input('search');

            $books = Book::where('title', 'like', "%{$query}%")
                ->orWhere('author', 'like', "%{$query}%")
                ->get();

            return response()->json($books, 200);
        }
        return response()->json(
            Book::all(),
            200
        );
    }

    // POST: Create a book
    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|string|max:30|unique:books,book_id,',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'year_published' => 'required|integer|digits:4',
        ]);

        $book = Book::create($data);

        return response()->json(['message'=>'Book Created Successfully.',
                                'data'=>$book], 201);
    }

    // GET: Display one book
    public function show(Book $book)
    {
        return response()->json($book, 200);
    }

    // PUT/PATCH: Update a book
    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'book_id' => 'required|string|max:30|unique:books,book_id,'. $book->id,
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'year_published' => 'required|integer|digits:4',
        ]);

        $book->update($data);

        return response()->json(['message'=>'Book Updated Successfully.',
                                'data'=>$book], 200);
    }

    // DELETE: Remove a book
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully.',
        ], 200);
    }
}
