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

        @foreach($mergedPullRequests as $mergedPullRequest)
        <item>
            <title>{{ $mergedPullRequest['title'] }}</title>
            <description><![CDATA[{{ $mergedPullRequest['description'] }}]]></description>
            <link>{{ $mergedPullRequest['link'] }}</link>
            <guid isPermaLink="{{ $mergedPullRequest['guid_is_permalink'] ?? 'true' }}">{{ $mergedPullRequest['guid'] ?? $mergedPullRequest['link'] }}</guid>
            <pubDate>{{ $mergedPullRequest['pubDate'] }}</pubDate>

            @if(isset($mergedPullRequest['author']))
            <author>{{ $mergedPullRequest['author'] }}</author>
            @endif

            @if(isset($mergedPullRequest['category']))
            @if(is_array($mergedPullRequest['category']))
            @foreach($mergedPullRequest['category'] as $category)
            <category>{{ $category }}</category>
            @endforeach
            @else
            <category>{{ $mergedPullRequest['category'] }}</category>
            @endif
            @endif

            @if(isset($mergedPullRequest['enclosure']))
            <enclosure url="{{ $mergedPullRequest['enclosure']['url'] }}" length="{{ $mergedPullRequest['enclosure']['length'] }}" type="{{ $mergedPullRequest['enclosure']['type'] }}" />
            @endif

            @if(isset($mergedPullRequest['source']))
            <source url="{{ $mergedPullRequest['source']['url'] }}">{{ $mergedPullRequest['source']['name'] }}</source>
            @endif
        </item>
        @endforeach
    </channel>
</rss>
