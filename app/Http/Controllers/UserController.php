<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Url;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        // Only fetch users tied to the same client as the logged-in admin
        $users = User::where('client_id', Auth::user()->client_id)->get();

        return view('admin.users.index', compact('users'));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $filter = $request->filter ?? 'this_month';

        $urlsQuery = Url::where('user_id', $user->id);

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
            case 'this_month':
            default:
                $urlsQuery->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                break;
        }

        $urls = $urlsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('member.dashboard', compact('user', 'urls', 'filter'));
    }

}
