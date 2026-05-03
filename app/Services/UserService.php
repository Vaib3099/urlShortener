<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminInvitation;
use App\Models\User;
use App\Repositories\ClientRepository;
use App\Repositories\UrlRepository;
use Carbon\Carbon;

class UserService
{
    protected $users;
    protected $urls;
    protected $clients;

    public function __construct(UserRepository $users, UrlRepository $urls, ClientRepository $clients)
    {
        $this->users = $users;
        $this->urls    = $urls;
        $this->clients = $clients;
    }

    public function getData($user, $filter)
    {
        switch ($user->role->name) {
            case 'superadmin':
                return $this->getSuperAdminDashboardData($filter);
            case 'admin':
                return $this->getAdminDashboardData($user, $filter);
            default:
                return $this->getMemberDashboardData($user, $filter);
        }  
    }

    private function getSuperAdminDashboardData($filter) {
        $clients = $this->clients->getClientsWithStats(5);

        $query = $this->urls->queryAll()->with('client');
        $query = $this->applyFilter($query, $filter);

        return [
            'clients'      => $clients,
            'urls'         => $query->orderBy('created_at','desc')->paginate(10),
            'filter'       => $filter
        ];
    }


    private function getMemberDashboardData($user, $filter)
    {
        $query = $this->urls->queryByUser($user->id);
        $query = $this->applyFilter($query, $filter);

        return [
            'user' => $user,
            'urls' => $query->orderBy('created_at','desc')->paginate(10),
            'filter' => $filter
        ];
    }

    private function getAdminDashboardData($user, $filter)
    {
        $clientId = $user->client_id;

        $members = User::where('client_id', $clientId)
                       ->where('id', '!=', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        // URLs
        $query = $this->urls->queryByClient($clientId);
        $query = $this->applyFilter($query, $filter);

        return [
            'members'   => $members,
            'urls'      => $query->orderBy('created_at','desc')->paginate(10),
            'filter'    => $filter
        ];
    }

    private function applyFilter($query, $filter)
    {
        switch ($filter) {
            case 'today': return $query->whereDate('created_at', Carbon::today());
            case 'last_week': return $query->whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ]);
            case 'last_month': return $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                            ->whereYear('created_at', Carbon::now()->subMonth()->year);
            default: return $query->whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year);
        }
    }

    public function inviteUser(array $data)
    {
        $role = Role::where('name', $data['role'])->firstOrFail();

        $tempPassword = "Testing@123";

        $user = $this->users->create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($tempPassword),
            'role_id'   => $role->id,
            'client_id' => Auth::user()->client_id,
        ]);

        Mail::to($user->email)->send(new AdminInvitation($user, $tempPassword));

        return $user;
    }

    public function getClientUsers($paginate = 10)
    {
        $clientId = Auth::user()->client_id;
        $excludeUserId = Auth::id();

        return $this->users->getUsersByClient($clientId, $excludeUserId, $paginate);
    }
}
