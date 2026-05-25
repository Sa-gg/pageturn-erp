<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount('books');

        if ($request->has('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        $authors = $query->orderBy('name')->paginate(20);

        return response()->json($authors);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'photo_url' => 'nullable|url|max:500',
        ]);

        $author = Author::create($request->only(['name', 'bio', 'photo_url']));

        return response()->json([
            'message' => 'Author created',
            'author' => $author,
        ], 201);
    }

    public function show($id)
    {
        $author = Author::with('books')->findOrFail($id);
        return response()->json(['author' => $author]);
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'photo_url' => 'nullable|url|max:500',
        ]);

        $author->update($request->only(['name', 'bio', 'photo_url']));

        return response()->json([
            'message' => 'Author updated',
            'author' => $author,
        ]);
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();

        return response()->json(['message' => 'Author deleted']);
    }
}
