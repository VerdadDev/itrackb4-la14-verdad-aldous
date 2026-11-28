<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Models\Comic;
use App\Models\Genre;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->query('search');
        $genreId = $request->query('genre_id');
        $sort    = $request->query('sort', 'title');

        if (! in_array($sort, ['title', 'rate'])) {
            $sort = 'title';
        }

        $genres = Genre::all();

        $query = Comic::with('genre');

        if ($search) {
            $query = $query->where('title', 'like', "%{$search}%");
        }

        if ($genreId) {
            $query->where('genre_id', $genreId);
        }

        $comics = Comic::with('genre')
            ->orderBy($sort, $sort === 'rate' ? 'desc' : 'asc')
            ->paginate(5)
            ->withQueryString();

        return view('comics.index', compact('comics', 'genres', 'sort'));
    }

    public function create()
    {
        Gate::authorize('create', Comic::class);
        $genres = Genre::all();
        return view('comics.create', compact('genres'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Comic::class);
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'genre_id'     => 'required|exists:genres,id',
            'rate'         => 'required|numeric|min:1|max:5',
            'chapter'      => 'required|numeric|min:1|max:2000',
            'is_completed' => 'required|in:1,0',
        ]);

        Comic::create($validated);
        return redirect()->route('comics.index')->with('success', 'Comic created successfully.');
    }

    public function show(Comic $comic)
    {
        return view('comics.show', compact('comic'));
    }

    public function edit(Comic $comic)
    {
        Gate::authorize('update', $comic);
        $genres = Genre::all();
        return view('comics.edit', compact('comic', 'genres'));
    }

    public function update(Request $request, Comic $comic)
    {
        Log::info('Comic updated', ['id' => $comic->id]);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'genre_id'     => 'required|exists:genres,id',
            'rate'         => 'required|numeric|min:1|max:5',
            'chapter'      => 'required|numeric|min:1|max:2000',
            'is_completed' => 'required|in:1,0',
        ]);

        $comic->update($validated);
        return redirect()->route('comics.index')->with('success', 'Comic updated successfully.');
    }

    public function destroy(Comic $comic)
    {
        Gate::authorize('delete', $comic);
        $comic->delete();
        return redirect()->route('comics.index')->with('success', 'Comic deleted successfully.');
    }

    public function filter($value)
    {
        return redirect()->route('comics.index', ['genre_id' => $value]);
    }
}
