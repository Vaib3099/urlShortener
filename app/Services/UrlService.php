<?php

namespace App\Services;

use App\Repositories\UrlRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class UrlService
{
    protected $urls;

    public function __construct(UrlRepository $urls)
    {
        $this->urls = $urls;
    }

    public function listUrls($paginate = 10)
    {
        $user = Auth::user();

        if ($user->role->name === 'superadmin') {
            return $this->urls->queryAll()->latest()->paginate($paginate);
        } elseif ($user->role->name === 'admin') {
            return $this->urls->queryByClient($user->client_id)->latest()->paginate($paginate);
        } else {
            return $this->urls->queryByUser($user->id)->latest()->paginate($paginate);
        }
    }

    public function shortenUrl(string $originalUrl)
    {
        $shortCode = str()->random(20);

        return $this->urls->create([
            'user_id'      => Auth::id(),
            'client_id'    => Auth::user()->client_id,
            'original_url' => $originalUrl,
            'short_code'   => $shortCode,
        ]);
    }

    public function redirectUrl(string $shortCode)
    {
        $url = $this->urls->findByShortCode($shortCode);
        $url->increment('hits');
        return $url->original_url;
    }

    public function downloadUrls(string $filter)
    {
        $user = Auth::user();

        if ($user->role->name === 'member') {
            $urlsQuery = $this->urls->queryByUser($user->id);
        } elseif ($user->role->name === 'admin') {
            $urlsQuery = $this->urls->queryByClient($user->client_id);
        } else {
            $urlsQuery = $this->urls->queryAll();
        }

        switch ($filter) {
            case 'today':
                $urlsQuery->whereDate('created_at', Carbon::today());
                break;
            case 'last_week':
                $urlsQuery->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ]);
                break;
            case 'last_month':
                $urlsQuery->whereMonth('created_at', Carbon::now()->subMonth()->month)
                          ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;
            default:
                $urlsQuery->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
        }

        $urls = $urlsQuery->with(['user','client'])->get();

        $csvData = [];
        $header = ['Short URL', 'Long URL', 'Hits', 'Created On'];
        if ($user->role->name === 'admin') {
            $header[] = 'Member';
        } elseif ($user->role->name === 'superadmin') {
            $header[] = 'Client';
        }
        $csvData[] = $header;

        foreach ($urls as $url) {
            $row = [
                url('/s/'.$url->short_code),
                $url->original_url,
                $url->hits ?? 0,
                $url->created_at->format('d M Y'),
            ];
            if ($user->role->name === 'admin') {
                $row[] = $url->user->name ?? '';
            } elseif ($user->role->name === 'superadmin') {
                $row[] = $url->client->name ?? '';
            }
            $csvData[] = $row;
        }

        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="urls.csv"',
        ]);
    }
}
