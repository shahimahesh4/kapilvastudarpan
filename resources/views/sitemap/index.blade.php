{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @for ($page = 1; $page <= $articleSitemapCount; $page++)
        <sitemap><loc>{{ $page === 1 ? route('sitemap.pages') : route('sitemap.pages.page', ['page' => $page]) }}</loc></sitemap>
    @endfor
    <sitemap><loc>{{ route('sitemap.news') }}</loc></sitemap>
</sitemapindex>
