<h3 class="mt-4 d-flex justify-content-between align-items-center">
    <span>{{ $isAdmin ? 'Shortened URLs' : 'Your Shortened URLs' }}</span>
    @if(!$isSuperAdmin)
        <a href="{{ route('urls.create') }}" class="btn btn-sm btn-success">
            Shorten New URL
        </a>
    @endif
    <a href="{{ route('urls.download', ['filter' => request('filter') ?? 'this_month']) }}"
       class="btn btn-sm btn-outline-success">
        Download URLs
    </a>
</h3>

<form method="GET" action="{{ $route }}" class="mb-3">
    <div class="input-group" style="max-width: 250px;">
        <select name="filter" class="form-select" onchange="this.form.submit()">
            <option value="today" {{ ($filter ?? 'this_month') == 'today' ? 'selected' : '' }}>Today</option>
            <option value="last_week" {{ ($filter ?? 'this_month') == 'last_week' ? 'selected' : '' }}>Last Week</option>
            <option value="last_month" {{ ($filter ?? 'this_month') == 'last_month' ? 'selected' : '' }}>Last Month</option>
            <option value="this_month" {{ ($filter ?? 'this_month') == 'this_month' ? 'selected' : '' }}>This Month</option>
        </select>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Short URL</th>
            <th>Long URL</th>
            <th>Hits</th>
            @if($isAdmin)
                <th>Member</th>
            @elseif($isSuperAdmin)
                <th>Client</th>
            @endif
            <th>Created On</th>
        </tr>
    </thead>
    <tbody>
        @forelse($urls as $url)
            <tr>
                <td>
                    <a href="{{ url('/s/'.$url->short_code) }}" target="_blank">
                        {{ url('/s/'.$url->short_code) }}
                    </a>
                </td>
                <td>{{ $url->original_url }}</td>
                <td>{{ $url->hits ?? 0 }}</td>
                @if($isAdmin)
                    <td>{{ $url->user->name }}</td>
                @elseif($isSuperAdmin)
                    <td>{{ $url->client->name }}</td>
                @endif
                <td>{{ $url->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $isAdmin || $isSuperAdmin ? 5 : 4 }}">No URLs found for this filter.</td>
            </tr>
        @endforelse
    </tbody>
</table>
