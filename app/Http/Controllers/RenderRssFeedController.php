<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PullRequest;

class RenderRssFeedController extends Controller
{
    public function __invoke(string $owner, string $repo)
    {
        $mergedPullRequests = PullRequest::query()
            ->whereHas('repository', fn ($query) => $query->where('owner', $owner)->where('repo', $repo))
            ->with([
                'author',
                'repository',
                'branch'
            ])
            ->latest('id')
            ->limit(20)
            ->get();

        return response()
            ->view('rss', compact('mergedPullRequests'))
            ->withHeaders([
                'Content-Type' => 'application/rss+xml',
            ]);
    }
}
