<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function getUsersByClient($clientId, $excludeUserId, $paginate = 10)
    {
        return User::where('client_id', $clientId)
                   ->where('id', '!=', $excludeUserId)
                   ->withCount('urls')
                   ->withSum('urls', 'hits')
                   ->orderBy('created_at', 'desc')
                   ->paginate($paginate);
    }
}
