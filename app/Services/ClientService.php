<?php 

namespace App\Services;

use App\Repositories\ClientRepository;
use App\Repositories\UserRepository;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminInvitation;
use Illuminate\Support\Facades\Log;

class ClientService
{
    protected $clients;
    protected $users;

    public function __construct(ClientRepository $clients, UserRepository $users)
    {
        $this->clients = $clients;
        $this->users   = $users;
    }

    public function createClientWithAdmin(array $data)
    {
        $client = $this->clients->create([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        $tempPassword = "Testing@123";

        $admin = $this->users->create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($tempPassword),
            'role_id'   => $adminRole->id,
            'client_id' => $client->id,
        ]);

        try {
            Mail::to($admin->email)->send(new AdminInvitation($admin, $tempPassword));
        } catch (\Exception $e) {
            Log::error("Failed to send invitation", [
                'user' => $admin->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $admin;
    }

    public function listClients($paginate = 10)
    {
        return $this->clients->getClientsWithStats($paginate);
    }
}
