<x-layouts.app
    :title="$heading . ' | कपिलवस्तु दर्पण'"
    :description="'कपिलवस्तु दर्पण — ' . $heading . ' सम्बन्धी सम्पूर्ण ताजा समाचार, विचार, भिडियो र विश्लेषण।'"
    :keywords="'कपिलवस्तु दर्पण, ' . $heading . ', नेपाली समाचार, ताजा अपडेट'"
>
    <main class="mx-auto max-w-[1280px] px-4 py-8 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-4 flex items-center gap-2 text-xs font-bold text-stone-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-[#c92137]">गृहपृष्ठ</a>
            <span>/</span>
            <span class="text-[#c92137]">{{ $heading }}</span>
        </nav>

        <div class="grid gap-8 lg:grid-cols-12">
            <!-- Left 8 Columns: News Grid & Pagination -->
            <section class="lg:col-span-8">
                <div class="mb-6 flex items-end justify-between border-b-2 border-[#c92137] pb-3">
                    <h1 class="text-3xl font-extrabold text-[#c92137] flex items-center gap-2">
                        <span class="h-6 w-1.5 bg-[#c92137] rounded"></span> {{ $heading }}
                    </h1>
                    <span class="text-xs font-bold text-stone-500">कुल समाचार: {{ $articles->total() }}</span>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    @forelse($articles as $article)
                        <article class="rounded-xl border border-stone-200 bg-white p-4 shadow-sm hover:shadow-md transition group">
                            <a href="{{ route('articles.show', $article->slug) }}" class="block aspect-[16/9] rounded-lg overflow-hidden bg-stone-100">
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                            </a>
                            <div class="mt-3 flex items-center gap-2">
                                @php $cardCategory = $article->categories->first() ?? $article->category; @endphp
                                @if($cardCategory)
                                    <a href="{{ route('category.show', $cardCategory->slug) }}" class="text-xs font-bold text-[#c92137] hover:underline">
                                        {{ $cardCategory->name }}
                                    </a>
                                @endif
                                @if($article->province)
                                    <span class="text-stone-300">•</span>
                                    <span class="text-[11px] font-semibold text-stone-500">{{ $article->province }}</span>
                                @endif
                            </div>
                            <h2 class="mt-1.5">
                                <a href="{{ route('articles.show', $article->slug) }}" class="block text-lg font-extrabold leading-snug text-stone-900 group-hover:text-[#c92137] hover:underline line-clamp-2">
                                    {{ $article->title }}
                                </a>
                            </h2>
                            <p class="mt-2 text-xs text-stone-600 line-clamp-2 leading-relaxed">{{ $article->excerpt }}</p>
                            <div class="mt-3 flex items-center justify-between border-t border-stone-100 pt-2 text-[11px] text-stone-400">
                                <span>{{ $article->author }}</span>
                                <time>{{ $article->published_at?->diffForHumans() }}</time>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full py-16 text-center">
                            <p class="text-lg font-bold text-stone-600">यस शीर्षक अन्तर्गत कुनै समाचार फेला परेन।</p>
                            <a href="{{ route('home') }}" class="mt-4 inline-block rounded bg-[#c92137] px-4 py-2 text-sm font-bold text-white hover:bg-red-800 transition">
                                मुख्य पृष्ठमा जानुहोस्
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            </section>

            <!-- Right 4 Columns: Sidebar -->
            <aside class="lg:col-span-4 space-y-8">
                <!-- Trending News -->
                <div class="rounded-xl border border-red-200 bg-red-50/40 p-5 shadow-sm">
                    <div class="flex items-center justify-between border-b border-red-200 pb-3 mb-4">
                        <h3 class="text-lg font-extrabold text-[#c92137] flex items-center gap-1.5">
                            <span>🔥</span> ट्रेन्डिङ समाचार
                        </h3>
                        <span class="text-xs font-bold text-stone-500">धेरै हेरिएको</span>
                    </div>
                    <ol class="divide-y divide-red-100">
                        @foreach($trendingArticles as $trend)
                            <li class="flex gap-3 py-3 group">
                                <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-[#c92137] text-xs font-extrabold text-white shadow-sm">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <a href="{{ route('articles.show', $trend->slug) }}" class="text-sm font-bold leading-snug text-stone-800 group-hover:text-[#c92137] hover:underline line-clamp-2">
                                        {{ $trend->title }}
                                    </a>
                                    <span class="mt-1 block text-[10px] text-stone-400">{{ $trend->views }} पटक हेरियो</span>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- Sidebar Custom Banner Ad -->
                <aside aria-label="विज्ञापन">
                    <x-ad-banner placement="sidebar" class="aspect-square" />
                </aside>

                <!-- Categories List -->
                <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-extrabold text-stone-900 border-b border-stone-200 pb-3 mb-4 flex items-center gap-2">
                        <span class="h-4 w-1 bg-[#2e5cb0] rounded"></span> अन्य विधाहरू
                    </h3>
                    <ul class="divide-y divide-stone-100">
                        @foreach($categories as $c)
                            <li>
                                <a href="{{ route('category.show', $c->slug) }}" class="flex items-center justify-between py-2.5 text-sm font-bold text-stone-700 hover:text-[#c92137] transition">
                                    <span>{{ $c->name }}</span>
                                    <span class="text-xs font-normal text-stone-400">{{ $c->articles()->published()->count() }} लेख</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </main>
</x-layouts.app>
