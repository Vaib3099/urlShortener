<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UrlService;

class UrlController extends Controller
{
    protected $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    public function index()
    {
        $urls = $this->urlService->listUrls(10);
        return view('urls.index', compact('urls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url'
        ]);

        $url = $this->urlService->shortenUrl($request->original_url);

        return redirect()->back()->with('success', 'URL shortened: ' . url('/s/' . $url->short_code));
    }

    public function redirect($shortCode)
    {
        $originalUrl = $this->urlService->redirectUrl($shortCode);
        return redirect()->away($originalUrl);
    }

    public function download(Request $request)
    {
        $filter = $request->filter ?? 'this_month';
        return $this->urlService->downloadUrls($filter);
    }
}
