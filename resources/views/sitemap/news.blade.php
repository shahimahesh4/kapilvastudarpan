{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    @foreach ($articles as $article)
        <url>
            <loc>{{ article_url($article) }}</loc>
            <news:news>
                <news:publication><news:name>{{ setting('site_name', 'कपिलवस्तु दर्पण') }}</news:name><news:language>ne</news:language></news:publication>
                <news:publication_date>{{ $article->published_at?->toIso8601String() }}</news:publication_date>
                <news:title>{{ $article->title }}</news:title>
            </news:news>
        </url>
    @endforeach
</urlset>
