@extends('layouts.app')

@section('title', 'Add Comic')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Comic</h5>
                </div>
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('comics.store') }}">
                        @csrf

                        @include('comics._form', ['comic' => new App\Models\Comic()])

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i>Save Comic
                            </button>
                            <a href="{{ route('comics.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
