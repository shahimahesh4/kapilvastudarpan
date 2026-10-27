{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ url('/') }}</loc><changefreq>always</changefreq><priority>1.0</priority></url>
    @foreach ($categories as $category)
        <url><loc>{{ route('category.show', $category->slug) }}</loc><lastmod>{{ $category->updated_at?->toIso8601String() }}</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>
    @endforeach
    @foreach ($pages as $page)
        <url><loc>{{ route('pages.show', $page->slug) }}</loc><lastmod>{{ $page->updated_at?->toIso8601String() }}</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>
    @endforeach
    @foreach ($articles as $article)
        <url><loc>{{ route('articles.show', $article->slug) }}</loc><lastmod>{{ $article->updated_at?->toIso8601String() }}</lastmod><changefreq>daily</changefreq><priority>0.9</priority></url>
    @endforeach
</urlset>
