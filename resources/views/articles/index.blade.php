<x-layouts.app>
    <main class="mx-auto min-h-screen max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-7 flex items-end justify-between border-b-2 border-[#a50404] pb-3"><h1 class="text-3xl font-extrabold text-[#a50404]">{{ $heading }}</h1><a href="{{ route('home') }}" class="text-sm font-bold text-[#2e5cb0] hover:underline">गृहपृष्ठ</a></div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@forelse($articles as $article)<article class="border border-stone-200 bg-white p-4 shadow-sm"><a href="{{ route('articles.show', $article) }}" class="story-art story-art--small block aspect-[16/9]"></a><p class="mt-4 text-xs font-bold text-[#a50404]">{{ $article->category->name }}</p><h2><a href="{{ route('articles.show', $article) }}" class="mt-1 block text-xl font-extrabold leading-snug hover:text-[#a50404] hover:underline">{{ $article->title }}</a></h2><p class="mt-2 text-sm text-stone-600">{{ $article->excerpt }}</p></article>@empty<p class="col-span-full py-12 text-center text-stone-600">समाचार भेटिएन।</p>@endforelse</div>
        <div class="mt-8">{{ $articles->links() }}</div>
    </main>
</x-layouts.app>
