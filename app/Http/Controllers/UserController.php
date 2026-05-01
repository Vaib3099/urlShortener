<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Url;

class UserController extends Controller
{
    public function index()
    {
        // Only fetch users tied to the same client as the logged-in admin
        $users = User::where('client_id', Auth::user()->client_id)->get();

        return view('admin.users.index', compact('users'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Fetch only this member’s URLs
        $urls = Url::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        return view('member.dashboard', compact('user', 'urls'));
    }
}
