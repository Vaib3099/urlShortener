@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }} (Client: {{ Auth::user()->client->name }})</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Members</h5>
                    <p class="display-6">{{ $userCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-5 d-flex justify-content-between align-items-center">
        <span>Shortened URLs</span>
        <a href="{{ route('urls.create') }}" class="btn btn-sm btn-success">
            Shorten New URL
        </a>
    </h3>

    <!-- Filters -->
    <div class="mb-3">
        <a href="{{ route('admin.dashboard', ['filter' => 'today']) }}" class="btn btn-outline-primary btn-sm">Today</a>
        <a href="{{ route('admin.dashboard', ['filter' => 'last_week']) }}" class="btn btn-outline-primary btn-sm">Last Week</a>
        <a href="{{ route('admin.dashboard', ['filter' => 'last_month']) }}" class="btn btn-outline-primary btn-sm">Last Month</a>
        <a href="{{ route('admin.dashboard', ['filter' => 'this_month']) }}" class="btn btn-outline-primary btn-sm">This Month</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Original URL</th>
                <th>Short URL</th>
                <th>Created By</th>
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
                    <td>{{ $url->user->name }}</td>
                    <td>{{ $url->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No URLs found for this filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex">
        {{ $urls->links() }}
        <h3 class="ms-3">
            <a href="{{ url('/urls') }}" class="btn btn-sm btn-primary">
                View More
            </a>
        </h3>
    </div>

    <h3 class="mt-5 d-flex justify-content-between align-items-center">
        <span>Team Members & Admins</span>
        <!-- Invite Team Member link -->
        <a href="{{ url('/admin/create-user') }}" class="btn btn-sm btn-primary">
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
            <a href="{{ url('/members') }}" class="btn btn-sm btn-primary">
                View More
            </a>
        </h3>
    </div>
</div>
@endsection
