<?php

namespace App\Repositories;

use App\Models\Url;

class UrlRepository
{
    public function queryByUser($userId)
    {
        return Url::where('user_id', $userId);
    }

    public function queryByClient($clientId)
    {
        return Url::where('client_id', $clientId);
    }

    public function queryAll()
    {
        return Url::query();
    }

    public function create(array $data): Url
    {
        return Url::create($data);
    }

    public function findByShortCode(string $shortCode): Url
    {
        return Url::where('short_code', $shortCode)->firstOrFail();
    }
}

