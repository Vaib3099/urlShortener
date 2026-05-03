@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Superadmin Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}</p>

    @include('partials.clients-table', ['clients' => $clients])

    <div class="d-flex">
        <h3 class="ms-3">
            <a href="{{ route('superadmin.clients') }}" class="btn btn-sm btn-primary">
                View More
            </a>
        </h3>
    </div>


    @include('partials.url-table', ['urls' => $urls, 'isAdmin' => false, 'isSuperAdmin' => true, 'viewMore' => true, 'route' => route('user.dashboard')])
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
