<x-layouts.app>
    <article class="mx-auto min-h-screen max-w-4xl px-4 py-10 sm:px-6">
        <a href="{{ route('category.show', $article->category) }}" class="text-sm font-extrabold text-[#a50404] hover:underline">{{ $article->category->name }}</a>
        <h1 class="mt-3 text-3xl font-extrabold leading-tight text-stone-900 sm:text-5xl">{{ $article->title }}</h1>
        <p class="mt-4 text-sm text-stone-600">{{ $article->author }} · {{ $article->published_at->translatedFormat('Y F j') }}</p>
        <div class="story-art story-art--lead mt-7 aspect-[16/8]"></div>
        <div class="prose prose-lg mt-8 max-w-none text-stone-800">{!! nl2br(e($article->body ?: $article->excerpt)) !!}</div>
    </article>
</x-layouts.app>
