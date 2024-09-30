<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\ShortenedUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShortenedUrlController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {

        $search = $request->get('search', '');

        $shortenedUrls = ShortenedUrl::where('user_id', Auth::id())
            ->when($search, function ($query, $search) {
                return $query->search($search);
            })
            ->paginate(10);

        return view('backend.url.index', [
            'urls'   => $shortenedUrls,
            'search' => $search,
        ]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
            'custom_url'   => 'nullable|string|alpha_dash|max:20',
        ]);

        $baseUrl  = $request->input('custom_url') ?: Str::random(6);
        $shortUrl = ShortenedUrl::generateUniqueShortUrl($baseUrl);

        $shortenedUrl = ShortenedUrl::create([
            'user_id'      => Auth::id(),
            'original_url' => $request->original_url,
            'short_url'    =>  $shortUrl,
        ]);

        return redirect()->route('short-url.index')->with('success', 'Shortened URL created: ' . $shortenedUrl->short_url);
    }

    /**
     * @param $slug
     */
    public function destroy($slug)
    {

        $shortenedUrl = ShortenedUrl::where('slug', $slug)->firstOrFail();

        if ($shortenedUrl->user_id !== Auth::id()) {
            return redirect()->route('short-url.index')->with('error', 'You are not authorized to delete this URL.');
        }

        $shortenedUrl->delete();

        return redirect()->route('short-url.index')->with('success', 'Shortened URL deleted successfully.');
    }

    /**
     * @param $shortUrl
     */
    public function shortener($shortUrl)
    {

        $shortenedUrl = ShortenedUrl::where('short_url', $shortUrl)->firstOrFail();
        $shortenedUrl->recordClick();

        return redirect()->to($shortenedUrl->original_url);
    }

    /**
     * @param $slug
     */
    public function stats($slug)
    {
        $shortenedUrl = ShortenedUrl::where('slug', $slug)->where('user_id', Auth::id())->with('clicks')->firstOrFail();

        return view('backend.url.stats', [
            'shortenedUrl' => $shortenedUrl,
        ]);
    }
}
