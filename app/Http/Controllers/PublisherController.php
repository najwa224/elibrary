<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;

class PublisherController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'pname' => 'required|string|max:255',
        'city' => 'required|string|max:255',
    ]);

    $publisher = Publisher::create($validated);

    return response()->json([
        'message' => 'Publisher added successfully',
        'publisher' => $publisher
    ], 201);
}


public function search(Request $request)
{
    $query = $request->query('name');

    if (!$query) {
        return response()->json(['message' => 'يرجى إدخال اسم الناشر للبحث'], 400);
    }

    // البحث عن الناشرين بالاسم مع كتبهم ومؤلفي الكتب
    $publishers = Publisher::where('pname', 'LIKE', "%$query%")
        ->with(['books.author']) // علاقات: الكتب ← المؤلفين
        ->get();

    $results = $publishers->map(function ($publisher) {
        return [
            'publisher' => $publisher->pname,
            'books' => $publisher->books->map(function ($book) {
                return [
                    'title' => $book->title,
                    'type' => $book->type,
                    'price' => $book->price,
                    'author' => $book->author
                        ? $book->author->fname . ' ' . $book->author->lname
                        : null,
                ];
            }),
        ];
    });

    return response()->json($results);
}


}
