<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminInvitation;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Url;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Show form for superadmin to create admin
    public function showCreateForm()
    {
        return view('superadmin.create-admin');
    }

    // Store new admin and send invitation
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:clients,name',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        // Find admin role
        $adminRole = Role::where('name', 'admin')->first();

        // Generate temporary password
        $tempPassword = "Testing@123"; // For testing purposes, use a fixed password. In production, use str()->random(10);

        // Create admin user
        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($tempPassword),
            'role_id'  => $adminRole->id,
            'client_id'=> $client->id,
        ]);

        try {
            // Send invitation email
            Mail::to($admin->email)->send(new AdminInvitation($admin, $tempPassword));
        } catch (\Exception $e) {
            print_r($tempPassword);

        }

        return redirect()->back()->with('success', 'Admin created and invitation sent!');
    }

    // Admin dashboard
    public function dashboard(Request $request)
    {
        $clientId = Auth::user()->client_id;

        $userCount   = User::where([
            ['client_id', $clientId],
            ['id', '!=', Auth::id()]
        ])->count();
        $members = User::where([
            ['client_id', $clientId],
            ['id', '!=', Auth::id()]
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        // URL listing with filters
        $urlsQuery = Url::where('client_id', $clientId);

        switch ($request->filter) {
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
            case 'this_month':
                $urlsQuery->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                break;
        }

        $urls = $urlsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.dashboard', compact('userCount', 'members', 'urls'));
    }

    // Admin creates users under them
    public function showCreateUserForm()
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|in:user,admin',
        ]);

        $role = Role::where('name', $request->role)->firstOrFail();
        $tempPassword = str()->random(10); // generate secure random password

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($tempPassword),
            'role_id'   => $role->id,
            'client_id' => Auth::user()->client_id, // tie to admin’s client
        ]);

        Mail::to($user->email)->send(new AdminInvitation($user, $tempPassword));

        return redirect()->back()->with('success', 'User invited successfully!');
    }
}
