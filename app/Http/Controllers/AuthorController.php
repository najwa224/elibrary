<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $author = Author::create($validated);

        return response()->json([
            'message' => 'Author added successfully',
            'author' => $author
        ], 201);
    }


   

public function search(Request $request)
{
    $query = $request->query('name');

    if (!$query) {
        return response()->json(['message' => 'يرجى إدخال اسم للبحث'], 400);
    }

    // البحث عن المؤلفين حسب الاسم الأول أو الأخير
    $authors = Author::where('fname', 'LIKE', "%$query%")
        ->orWhere('lname', 'LIKE', "%$query%")
        ->with(['books.publisher']) // جلب كتب وناشر كل كتاب
        ->get();

    // تجهيز النتيجة
    $results = $authors->map(function ($author) {
        return [
            'author' => $author->fname . ' ' . $author->lname,
            'books' => $author->books->map(function ($book) {
                return [
                    'title' => $book->title,
                    'type' => $book->type,
                    'price' => $book->price,
                    'publisher' => $book->publisher ? $book->publisher->pname : null,
                ];
            }),
        ];
    });

    return response()->json($results);
}

}
