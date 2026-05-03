@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Member Dashboard</h1>
    <p>Welcome, {{ $user->name }}</p>

    @include('partials.url-table', ['urls' => $urls, 'isAdmin' => false, 'isSuperAdmin' => false, 'viewMore' => false, 'route' => route('user.dashboard')])
    <div class="d-flex">
        {{ $urls->links() }}
    </div>
</div>
@endsection
