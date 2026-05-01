@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Shortened URLs</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Original URL</th>
                <th>Short URL</th>
                <th>Created By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($urls as $url)
                <tr>
                    <td>{{ $url->original_url }}</td>
                    <td>
                        <a href="{{ url('/s/'.$url->short_code) }}" target="_blank">
                            {{ url('/s/'.$url->short_code) }}
                        </a>
                    </td>
                    <td>{{ $url->user->name }}</td>
                    <td>{{ $url->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No URLs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $urls->links() }}
    </div>
</div>
@endsection
