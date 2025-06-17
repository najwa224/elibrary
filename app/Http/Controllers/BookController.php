<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;


class BookController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|string',
        'price' => 'required|numeric',
        'pub_id' => 'required|exists:publishers,id',
        'author_id' => 'required|exists:authors,id',
    ]);

    $book = Book::create($validated);

    return response()->json([
        'message' => 'Book added successfully',
        'book' => $book
    ], 201);
}




public function index()
{
    $books = Book::with(['author', 'publisher'])->get();

    $data = $books->map(function ($book) {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'type' => $book->type,
            'price' => $book->price,
            'author' => $book->author ? [
                'name' => trim($book->author->fname . ' ' . $book->author->lname),
                'country' => $book->author->country,
                'city' => $book->author->city,
            ] : null,
            'publisher' => $book->publisher ? [
                'name' => $book->publisher->pname,
                'city' => $book->publisher->city,
            ] : null,
        ];
    });

    return response()->json($data);
}


public function search(Request $request)
{
    $query = $request->query('title');

    if (!$query) {
        return response()->json(['message' => 'يرجى إدخال عنوان للبحث'], 400);
    }

    // ✅ جلب الكتب مع المؤلف والناشر
    $books = Book::with(['author', 'publisher'])
        ->where('title', 'LIKE', "%$query%")
        ->get();

    // ✅ تجهيز النتيجة بنفس التنسيق المستخدم في index()
    $result = $books->map(function ($book) {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'type' => $book->type,
            'price' => $book->price,
            'author' => $book->author ? [
                'name' => $book->author->fname . ' ' . $book->author->lname,
                'country' => $book->author->country,
                'city' => $book->author->city,
            ] : null,
            'publisher' => $book->publisher ? [
                'name' => $book->publisher->pname,
                'city' => $book->publisher->city,
            ] : null,
        ];
    });

    return response()->json($result);
}





}
