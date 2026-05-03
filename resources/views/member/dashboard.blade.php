@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Member Dashboard</h1>
    <p>Welcome, {{ $user->name }}</p>

    @include('partials.url-table', ['urls' => $urls, 'isAdmin' => false, 'isSuperAdmin' => false, 'viewMore' => false, 'route' => route('user.dashboard')])
    <div class="d-flex">
        @if($viewMore ?? true)
            <h3 class="ms-3">
                <a href="{{ route('urls.index') }}" class="btn btn-sm btn-primary">
                    View More
                </a>
            </h3>
        @endif
    </div>
</div>
@endsection
