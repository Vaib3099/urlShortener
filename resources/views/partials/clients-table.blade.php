<h3 class="mt-5 d-flex justify-content-between align-items-center">
    <span>Clients List</span>
    <a href="{{ route('superadmin.create-admin') }}" class="btn btn-sm btn-primary">
        Invite
    </a>
</h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Users</th>
            <th>Total URLs</th>
            <th>Total Hits</th>
        </tr>
    </thead>
    <tbody>
        @forelse($clients as $client)
            <tr>
                <td>{{ $client->name }}</td>
                <td>{{ $client->users_count }}</td>
                <td>{{ $client->urls_count }}</td>
                <td>{{ $client->urls_sum_hits ?? 0 }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">No clients found.</td>
            </tr>
        @endforelse
    </tbody>
</table>