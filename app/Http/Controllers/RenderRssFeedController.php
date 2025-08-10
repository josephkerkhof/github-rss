<?php

namespace App\Http\Controllers;

use App\Models\PullRequest;

class RenderRssFeedController extends Controller
{
    public function __invoke(string $owner, string $repo)
    {
        $mergedPullRequests = PullRequest::query()
            ->whereHas('repository', fn ($query) => $query->where('slug', "{$owner}/{$repo}"))
            ->get();

        return response()
            ->view('rss', compact('mergedPullRequests'))
            ->withHeaders([
                'Content-Type' => 'application/rss+xml',
            ]);
    }
}
