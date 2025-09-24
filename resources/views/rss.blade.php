<?php

declare(strict_types=1);

?>
{!! '<'.'?'.'xml version="1.0" encoding="UTF-8" ?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $feed['title'] ?? config('app.name') }}</title>
        <description>{{ $feed['description'] ?? 'Latest updates from ' . config('app.name') }}</description>
        <link>{{ $feed['link'] ?? url('/') }}</link>
        <atom:link href="{{ url()->current() }}" rel="self" type="application/rss+xml" />
        <language>{{ $feed['language'] ?? 'en-us' }}</language>
        <copyright>{{ $feed['copyright'] ?? 'Copyright © ' . date('Y') . ' ' . config('app.name') }}</copyright>
        <managingEditor>{{ $feed['managingEditor'] ?? config('mail.from.address') }}</managingEditor>
        <webMaster>{{ $feed['webMaster'] ?? config('mail.from.address') }}</webMaster>
        <pubDate>{{ $feed['pubDate'] ?? now()->toRssString() }}</pubDate>
        <lastBuildDate>{{ $feed['lastBuildDate'] ?? now()->toRssString() }}</lastBuildDate>
        <generator>Laravel {{ app()->version() }}</generator>
        <ttl>{{ $feed['ttl'] ?? 60 }}</ttl>

        @if(isset($feed['image']))
        <image>
            <url>{{ $feed['image']['url'] }}</url>
            <title>{{ $feed['image']['title'] ?? $feed['title'] }}</title>
            <link>{{ $feed['image']['link'] ?? $feed['link'] }}</link>
            @if(isset($feed['image']['width']))
            <width>{{ $feed['image']['width'] }}</width>
            @endif
            @if(isset($feed['image']['height']))
            <height>{{ $feed['image']['height'] }}</height>
            @endif
        </image>
        @endif

        @foreach($mergedPullRequests as $pullRequest)
        <item>
            <title>{{ $pullRequest->title }}</title>
            <description><![CDATA[
                @if($pullRequest->body)
                {!! Str::of($pullRequest->body)
                ->markdown()
                ->toString() !!}
                @else
                No description provided.
                @endif
                ]]></description>
            <link>{{ $pullRequest->url }}</link>
            <guid isPermaLink="true">{{ $pullRequest->url }}</guid>
            <pubDate>{{ $pullRequest->merged_at->toRssString() }}</pubDate>

            {{-- If you have author relationship loaded, you can use it --}}
            @if(isset($pullRequest->author) && $pullRequest->author)
            <author>{{ $pullRequest->author->username }}</author>
            <category>{{ $pullRequest->author->username }}</category>
            @endif

            {{-- If you have repository relationship loaded --}}
            @if(isset($pullRequest->repository) && $pullRequest->repository)
            <category>{{ $pullRequest->repository->name }}</category>
            @endif

            {{-- If you have branch relationship loaded --}}
            @if(isset($pullRequest->branch) && $pullRequest->branch)
            <category>Target Branch: {{ $pullRequest->branch->name }}</category>
            @endif

            {{-- Custom elements for additional PR data --}}
            @if($pullRequest->created_at)
            <source url="{{ $pullRequest->url }}">Created: {{ $pullRequest->created_at->toRssString() }}</source>
            @endif
        </item>
        @endforeach
    </channel>
</rss>
<?php 
