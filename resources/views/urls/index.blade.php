@extends('layouts.app')

@section('content')
<div class="container">

    @include('partials.url-table', ['urls' => $urls, 'isAdmin' => Auth::user()->role->name === 'admin', 'isSuperAdmin' => Auth::user()->role->name === 'superadmin', 'viewMore' => false, 'route' => route('urls.index')])
    <div class="d-flex">
        {{ $urls->links() }}
    </div>
</div>
@endsection
