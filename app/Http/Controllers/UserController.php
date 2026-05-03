<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function index()
    {
        $members = $this->userService->getClientUsers(10);
        return view('admin.users.index', compact('members'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|in:member,admin',
        ]);

        $this->userService->inviteUser($request->only('name','email','role'));

        return redirect()->back()->with('success', 'User invited successfully!');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $filter = $request->filter ?? 'this_month';

        switch ($user->role->name) {
            case 'superadmin':
                return view('superadmin.dashboard', $this->userService->getData($user, $filter));
            case 'admin':
                return view('admin.dashboard', $this->userService->getData($user, $filter));
            default:
                return view('member.dashboard', $this->userService->getData($user, $filter));
        }
    }

}
