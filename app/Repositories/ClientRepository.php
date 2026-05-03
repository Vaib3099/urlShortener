<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function create(array $data): Client
    {
        return Client::create($data);
    }
    
    public function getClientsWithStats($paginate = 10)
    {
        return Client::withCount('users')
                     ->withCount('urls')
                     ->withSum('urls', 'hits')
                     ->orderBy('created_at', 'desc')
                     ->paginate($paginate);
    }
}
