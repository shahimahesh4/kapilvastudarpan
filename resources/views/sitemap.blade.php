{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach ($categories as $cat)
        <url>
            <loc>{{ route('category.show', $cat->slug) }}</loc>
            <changefreq>hourly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    @if(isset($pages))
        @foreach ($pages as $p)
            <url>
                <loc>{{ route('pages.show', $p->slug) }}</loc>
                <lastmod>{{ $p->updated_at->toIso8601String() }}</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.7</priority>
            </url>
        @endforeach
    @endif
    @foreach ($articles as $art)
        <url>
            <loc>{{ route('articles.show', $art->slug) }}</loc>
            <lastmod>{{ $art->updated_at->toIso8601String() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
            <news:news>
                <news:publication>
                    <news:name>कपिलवस्तु दर्पण</news:name>
                    <news:language>ne</news:language>
                </news:publication>
                <news:publication_date>{{ $art->published_at?->toIso8601String() }}</news:publication_date>
                <news:title>{{ $art->title }}</news:title>
            </news:news>
        </url>
    @endforeach
</urlset>
