@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Users for {{ Auth::user()->client->name }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No users found for this client.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
