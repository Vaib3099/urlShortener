@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Member Dashboard</h1>
    <p>Welcome, {{ $user->name }}</p>

    <h3 class="mt-4 d-flex justify-content-between align-items-center">
        <span>Your Shortened URLs</span>
        <a href="{{ route('urls.create') }}" class="btn btn-sm btn-success">
            Shorten New URL
        </a>
    </h3>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Original URL</th>
                <th>Short URL</th>
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
                    <td>{{ $url->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">You haven’t shortened any URLs yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $urls->links() }}
    </div>
</div>
@endsection
