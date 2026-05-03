@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Team Members</h1>
    <p>Client: {{ Auth::user()->client->name }}</p>

    <div class="mb-3">
        <a href="{{ route('admin.create-user') }}" class="btn btn-sm btn-primary">
            Invite New Member
        </a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Total URLs</th>
                <th>Total Hits</th>
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
                    <td>{{ $member->urls_count }}</td>
                    <td>{{ $member->urls_sum_hits ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No members found for this client.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $members->links() }}
    </div>
</div>
@endsection
