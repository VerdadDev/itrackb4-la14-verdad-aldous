@extends('layouts.app')

@section('title', 'Comics')

@section('content')


    @can('create', App\Models\Comic::class)
    @auth
    <a href="{{ route('comics.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Character
    </a>        
    @endauth
    @endcan

    @cannot('create', App\Models\Comic::class)

    <p class="text-muted small">You are viewing this list in read-only mode.</p>

    @endcannot

    <form method="GET" action="{{ route('comics.index') }}">
    <input type="text" name="search" value="{{ request('search') }}">


    <select name="genre_id">
        <option value="">All Genre</option>
        @foreach ($genres as $genre)
            <option value="{{ $genre->id }}"
                @selected(request('genre_id') == $genre->id)>{{ $genre->name }}</option>
        @endforeach
    </select>


    <select name="sort">
        <option value="">Any</option>
        <option value="title" @selected($sort === 'title')>Sort by title</option>
        <option value="0" @selected($sort === 'rate')>Sort by rate</option>
    </select>


    <button>Apply</button>
    <a href="{{ route('comics.index') }}">Clear</a>
</form>


    {{-- ── Table ─────────────────────────────────────────────── --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th class="text-center">Rating</th>
                        <th class="text-center">Chapters</th>
                        <th class="text-center">Status</th>
                        @auth
                        <th class="text-center" style="width:140px">Actions</th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comics as $comic)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('comics.show', $comic->id) }}"
                                   class="fw-semibold text-decoration-none">
                                    {{ $comic->title }}
                                </a>
                            </td>
                            <td>{{ $comic->author }}</td>
                            <td>
                                <span class="badge rounded-pill bg-primary-subtle text-primary">
                                    {{ $comic->genre->name }}
                                </span>
                            </td>
                            <td class="text-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $comic->rate ? '-fill text-warning' : ' text-secondary opacity-25' }}"></i>
                                @endfor
                            </td>
                            <td class="text-center">{{ $comic->chapter }}</td>
                            <td class="text-center">
                                @if($comic->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning text-dark">Ongoing</span>
                                @endif
                            </td>
                            @auth
                            <td class="text-center">
                                @can('update', $comic)
                                <a href="{{ route('comics.edit', $comic->id) }}"
                                   class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('delete', $comic)
                                <form action="{{ route('comics.destroy', $comic->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete \'{{ addslashes($comic->title) }}\'?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No comics found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $comics->links()}}
        </div>
    </div>

@endsection
