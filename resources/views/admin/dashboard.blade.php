@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }} (Client: {{ Auth::user()->client->name }})</p>

    @include('partials.url-table', ['urls' => $urls, 'isAdmin' => true, 'isSuperAdmin' => false, 'viewMore' => true, 'route' => route('user.dashboard')])
    <div class="d-flex">
        @if($viewMore ?? true)
            <h3 class="ms-3">
                <a href="{{ route('urls.index') }}" class="btn btn-sm btn-primary">
                    View More
                </a>
            </h3>
        @endif
    </div>

    <h3 class="mt-5 d-flex justify-content-between align-items-center">
        <span>Team Members & Admins</span>
        <!-- Invite Team Member link -->
        <a href="{{ route('admin.create-user') }}" class="btn btn-sm btn-primary">
            Invite Team Member
        </a>
    </h3>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>
                        <span class="badge bg-{{ $member->role->name === 'admin' ? 'danger' : 'secondary' }}">
                            {{ ucfirst($member->role->name) }}
                        </span>
                    </td>
                    <td>{{ $member->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No members found for this client.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex">
        {{ $members->links() }}
        <h3 class="ms-3">
            <a href="{{ route('admin.members') }}" class="btn btn-sm btn-primary">
                View More
            </a>
        </h3>
    </div>
</div>
@endsection
