@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Member Dashboard</h1>
    <p>Welcome, {{ $user->name }}</p>

    @include('partials.url-table', ['urls' => $urls, 'isAdmin' => false, 'viewMore' => false])
</div>
@endsection
