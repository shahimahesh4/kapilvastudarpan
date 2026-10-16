<x-layouts.app
    :title="$article->title . ' | कपिलवस्तु दर्पण'"
    :description="$article->meta_description ?? $article->excerpt ?? Str::limit(strip_tags($article->body), 160)"
    :keywords="$article->meta_keywords ?? 'कपिलवस्तु दर्पण, ' . ($article->categories->isNotEmpty() ? $article->categories->pluck('name')->join(', ') : ($article->category?->name ?? 'समाचार')) . ', ' . $article->title"
    :ogType="'article'"
    :ogImage="$article->image_url"
    :article="$article"
>
    <!-- Main Article Container -->
    <main class="mx-auto max-w-[1280px] px-4 py-8 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-4 flex items-center gap-2 text-xs font-bold text-stone-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-[#c92137]">गृहपृष्ठ</a>
            <span>/</span>
            @php $primaryCategory = $article->categories->first() ?? $article->category; @endphp
            @if($primaryCategory)
                <a href="{{ route('category.show', $primaryCategory->slug) }}" class="text-[#c92137] hover:underline">
                    {{ $primaryCategory->name }}
                </a>
            @endif
            @if($article->province)
                <span>/</span>
                <span class="text-stone-600">{{ $article->province }}</span>
            @endif
        </nav>

        <div class="grid gap-8 lg:grid-cols-12">
            <!-- Left 8 Columns: Article Content -->
            <article class="lg:col-span-8 bg-white">
                <!-- Category Badges -->
                <div class="flex flex-wrap items-center gap-2">
                    @forelse($article->categories as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="inline-block rounded bg-red-100 px-3 py-1 text-xs font-extrabold text-[#c92137] hover:bg-[#c92137] hover:text-white transition">
                            {{ $cat->name }}
                        </a>
                    @empty
                        @if($article->category)
                            <a href="{{ route('category.show', $article->category->slug) }}" class="inline-block rounded bg-red-100 px-3 py-1 text-xs font-extrabold text-[#c92137] hover:bg-[#c92137] hover:text-white transition">
                                {{ $article->category->name }}
                            </a>
                        @endif
                    @endforelse
                </div>

                <!-- Headline -->
                <h1 class="mt-3 text-3xl font-extrabold leading-tight text-stone-900 sm:text-4xl lg:text-5xl">
                    {{ $article->title }}
                </h1>

                <!-- Excerpt Highlight -->
                @if ($article->excerpt)
                    <p class="mt-4 rounded-r-lg border-l-4 border-[#c92137] bg-stone-50 p-4 text-lg font-semibold leading-relaxed text-stone-700">
                        {{ $article->excerpt }}
                    </p>
                @endif

                <!-- Author & Publishing Meta -->
                <div class="mt-5 flex flex-wrap items-center justify-between gap-4 border-y border-stone-200 py-3 text-xs text-stone-600">
                    <div class="flex items-center gap-3">
                        <span class="profile-avatar h-10 w-10 rounded-full border border-stone-300" aria-hidden="true"></span>
                        <div>
                            <span class="block font-extrabold text-stone-900 text-sm">{{ $article->author }}</span>
                            <div class="flex items-center gap-2 text-stone-500 mt-0.5">
                                <time datetime="{{ $article->published_at?->toIso8601String() }}">
                                    {{ $article->published_at?->diffForHumans() }}
                                </time>
                                <span>•</span>
                                <span>{{ $article->estimated_reading_time }} मिनेट पढ्न सकिने</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-stone-500">
                        <span class="flex items-center gap-1 font-semibold">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ $article->views }} पटक पढिएको
                        </span>
                    </div>
                </div>

                <!-- Top Social Sharing Toolbar -->
                <div class="mt-4 flex flex-wrap items-center gap-2 border-b border-stone-200 pb-4">
                    <span class="text-xs font-bold text-stone-600 mr-2">सेयर गर्नुहोस्:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded bg-[#1877f2] px-3 py-1.5 text-xs font-bold text-white hover:bg-[#0c63d4] transition shadow-sm">
                        <span>Facebook</span>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded bg-black px-3 py-1.5 text-xs font-bold text-white hover:bg-stone-800 transition shadow-sm">
                        <span>𝕏 Post</span>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded bg-[#25d366] px-3 py-1.5 text-xs font-bold text-white hover:bg-[#1eb855] transition shadow-sm">
                        <span>WhatsApp</span>
                    </a>
                    <button type="button" onclick="copyShareLink('{{ url()->current() }}')" class="inline-flex items-center gap-1.5 rounded border border-stone-300 bg-stone-100 px-3 py-1.5 text-xs font-bold text-stone-700 hover:bg-stone-200 transition shadow-sm">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>लिङ्क कपी</span>
                    </button>
                    <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 rounded border border-stone-300 bg-stone-100 px-3 py-1.5 text-xs font-bold text-stone-700 hover:bg-stone-200 transition shadow-sm">
                        <span>प्रिन्ट 🖨</span>
                    </button>
                </div>

                <!-- Featured Image -->
                <figure class="mt-6">
                    <div class="aspect-[16/9] rounded-xl overflow-hidden bg-stone-100 shadow-md">
                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
                    </div>
                    <figcaption class="mt-2 text-center text-xs text-stone-500 italic">
                        तस्बिर: {{ $article->author }} / कपिलवस्तु दर्पण
                    </figcaption>
                </figure>

                <!-- Full Article Body -->
                <div class="article-body prose prose-lg prose-red mt-8 max-w-none text-stone-800 leading-relaxed space-y-5 text-lg">
                    {!! $article->body ?: nl2br(e($article->excerpt)) !!}
                </div>

                <!-- Source Attribution Link if present -->
                @if($article->source_url)
                    <div class="mt-8 rounded-lg bg-stone-50 p-4 border border-stone-200 text-xs text-stone-600 flex items-center justify-between">
                        <span>मूल सन्दर्भ स्रोत: <strong>{{ parse_url($article->source_url, PHP_URL_HOST) }}</strong></span>
                        <a href="{{ $article->source_url }}" target="_blank" rel="nofollow noopener noreferrer" class="font-bold text-[#c92137] hover:underline">
                            मूल समाचार हेर्नुहोस् ↗
                        </a>
                    </div>
                @endif

                <!-- In-content Advertisement -->
                <aside class="my-8" aria-label="विज्ञापन">
                    <x-ad-banner placement="article_incontent" class="aspect-[97/16] sm:aspect-[97/12] max-w-full" />
                </aside>

                <!-- Bottom Social Sharing & Feedback -->
                <div class="mt-10 rounded-xl bg-stone-50 p-6 border border-stone-200">
                    <h3 class="text-base font-extrabold text-stone-800">यो समाचार तपाईंलाई कस्तो लाग्यो? साथीहरूलाई सेयर गर्नुहोस्</h3>
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="rounded bg-[#1877f2] px-4 py-2 text-xs font-bold text-white hover:bg-[#0c63d4] transition">
                            Facebook मा सेयर
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener noreferrer" class="rounded bg-black px-4 py-2 text-xs font-bold text-white hover:bg-stone-800 transition">
                            𝕏 मा पोस्ट
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="rounded bg-[#25d366] px-4 py-2 text-xs font-bold text-white hover:bg-[#1eb855] transition">
                            WhatsApp मा पठाउनुहोस्
                        </a>
                        <button type="button" onclick="copyShareLink('{{ url()->current() }}')" class="rounded border border-stone-300 bg-white px-4 py-2 text-xs font-bold text-stone-700 hover:bg-stone-100 transition">
                            लिङ्क कपी गर्नुहोस्
                        </button>
                    </div>
                </div>

                <!-- Related News (सम्बन्धित समाचार) -->
                @if($relatedArticles->isNotEmpty())
                    <section class="mt-12 border-t-2 border-[#c92137] pt-6" aria-labelledby="related-heading">
                        <h2 id="related-heading" class="text-2xl font-extrabold text-stone-900 mb-6 flex items-center gap-2">
                            <span class="h-5 w-1.5 bg-[#c92137] rounded"></span> सम्बन्धित समाचार
                        </h2>
                        <div class="grid gap-6 sm:grid-cols-2">
                            @foreach($relatedArticles as $rel)
                                <article class="rounded-lg border border-stone-200 bg-white p-3 shadow-sm hover:shadow-md transition group">
                                    <a href="{{ route('articles.show', $rel->slug) }}" class="block aspect-[16/9] rounded overflow-hidden bg-stone-100">
                                        <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                    </a>
                                    <h3 class="mt-3">
                                        <a href="{{ route('articles.show', $rel->slug) }}" class="block text-base font-bold leading-snug text-stone-900 group-hover:text-[#c92137] hover:underline line-clamp-2">
                                            {{ $rel->title }}
                                        </a>
                                    </h3>
                                    <time class="mt-2 block text-[11px] text-stone-400">{{ $rel->published_at?->diffForHumans() }}</time>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </article>

            <!-- Right 4 Columns: Sidebar -->
            <aside class="lg:col-span-4 space-y-8">
                <!-- Trending News Box -->
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

                <!-- Latest News Box -->
                <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-extrabold text-stone-900 border-b border-stone-200 pb-3 mb-4 flex items-center gap-2">
                        <span class="h-4 w-1 bg-[#2e5cb0] rounded"></span> ताजा अपडेट
                    </h3>
                    <div class="divide-y divide-stone-100">
                        @foreach($latestArticles as $latest)
                            <article class="py-3 group">
                                @php $latestCat = $latest->categories->first() ?? $latest->category; @endphp
                                @if($latestCat)
                                    <a href="{{ route('category.show', $latestCat->slug) }}" class="text-[11px] font-bold text-[#c92137] hover:underline">
                                        {{ $latestCat->name }}
                                    </a>
                                @endif
                                <h4 class="mt-1">
                                    <a href="{{ route('articles.show', $latest->slug) }}" class="block text-sm font-bold leading-snug text-stone-800 group-hover:text-[#c92137] hover:underline line-clamp-2">
                                        {{ $latest->title }}
                                    </a>
                                </h4>
                                <time class="mt-1 block text-[10px] text-stone-400">{{ $latest->published_at?->diffForHumans() }}</time>
                            </article>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar Ad Box -->
                <aside aria-label="विज्ञापन">
                    <x-ad-banner placement="sidebar" class="aspect-square" />
                </aside>
            </aside>
        </div>
    </main>
</x-layouts.app>
