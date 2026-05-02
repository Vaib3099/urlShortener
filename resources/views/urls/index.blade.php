@extends('layouts.app')

@section('content')
<div class="container">

    @include('partials.url-table', ['urls' => $urls, 'isAdmin' => Auth::user()->role->name === 'admin', 'viewMore' => false])
</div>
@endsection
