<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClientService;

class ClientController extends Controller
{

    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:clients,email',
        ]);

        $this->clientService->createClientWithAdmin($request->only('name','email'));

        return redirect()->back()->with('success', 'Admin created and invitation sent!');
    }

    public function clients()
    {
        $clients = $this->clientService->listClients(10);
        return view('superadmin.clients', compact('clients'));
    }
}
