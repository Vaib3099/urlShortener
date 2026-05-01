<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Url;

class UrlController extends Controller
{
    // Show list of URLs (role-based visibility)
    public function index()
    {
        $user = Auth::user();

        if ($user->role->name === 'superadmin') {
            $urls = Url::latest()->paginate(10);
        } elseif ($user->role->name === 'admin') {
            $urls = Url::where('client_id', $user->client_id)->latest()->paginate(10);
        } else {
            $urls = Url::where('user_id', $user->id)->latest()->paginate(10);
        }

        return view('urls.index', compact('urls'));
    }

    // Store shortened URL
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url'
        ]);

        $shortCode = str()->random(6);

        $url = Url::create([
            'user_id'      => Auth::id(),
            'client_id'    => Auth::user()->client_id,
            'original_url' => $request->original_url,
            'short_code'   => $shortCode,
        ]);

        return redirect()->back()->with('success', 'URL shortened: ' . url('/s/' . $shortCode));
    }

    // Redirect short code to original URL
    public function redirect($shortCode)
    {
        $url = Url::where('short_code', $shortCode)->firstOrFail();
        return redirect()->away($url->original_url);
    }
}
