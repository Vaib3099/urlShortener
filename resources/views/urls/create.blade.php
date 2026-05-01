@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Shorten a URL</h2>

    <!-- Success / Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('urls.store') }}">
        @csrf

        <div class="mb-3">
            <label for="original_url" class="form-label">Enter Long URL</label>
            <input type="url" name="original_url" id="original_url" class="form-control" placeholder="https://example.com/very/long/link" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Shorten URL</button>
    </form>
</div>
@endsection
