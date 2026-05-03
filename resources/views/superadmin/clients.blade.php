@extends('layouts.app')

@section('content')
<div class="container">
    @include('partials.clients-table', ['clients' => $clients])

    <div class="d-flex justify-content-center">
        {{ $clients->links() }}
    </div>
</div>
@endsection
