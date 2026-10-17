@if ($paginator->hasPages())
    <nav role="navigation" aria-label="पृष्ठ नेभिगेसन" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-stone-400 bg-white border border-stone-200 cursor-not-allowed rounded-lg shadow-2xs">
                    « अघिल्लो
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-stone-700 bg-white border border-stone-200 rounded-lg shadow-2xs hover:bg-[#c92137] hover:text-white hover:border-[#c92137] transition">
                    « अघिल्लो
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-bold text-stone-700 bg-white border border-stone-200 rounded-lg shadow-2xs hover:bg-[#c92137] hover:text-white hover:border-[#c92137] transition">
                    पछिल्लो »
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-semibold text-stone-400 bg-white border border-stone-200 cursor-not-allowed rounded-lg shadow-2xs">
                    पछिल्लो »
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-stone-600">
                    देखाउँदै
                    <span class="font-bold text-stone-900">{{ nepali_number($paginator->firstItem()) }}</span>
                    देखि
                    <span class="font-bold text-stone-900">{{ nepali_number($paginator->lastItem()) }}</span>
                    सम्म (जम्मा
                    <span class="font-bold text-[#c92137]">{{ nepali_number($paginator->total()) }}</span>
                    समाचार)
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-xl shadow-2xs -space-x-px">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="अघिल्लो पृष्ठ">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-bold text-stone-300 bg-white border border-stone-200 cursor-not-allowed rounded-l-xl">
                                ‹
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-bold text-stone-600 bg-white border border-stone-200 rounded-l-xl hover:bg-[#c92137] hover:text-white hover:border-[#c92137] transition" aria-label="अघिल्लो पृष्ठ">
                            ‹
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-stone-500 bg-white border border-stone-200 cursor-default">
                                    {{ $element }}
                                </span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-black text-white bg-[#c92137] border border-[#c92137] cursor-default">
                                            {{ nepali_number($page) }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-stone-700 bg-white border border-stone-200 hover:bg-stone-50 hover:text-[#c92137] transition" aria-label="{{ nepali_number($page) }} पृष्ठमा जानुहोस्">
                                        {{ nepali_number($page) }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-sm font-bold text-stone-600 bg-white border border-stone-200 rounded-r-xl hover:bg-[#c92137] hover:text-white hover:border-[#c92137] transition" aria-label="पछिल्लो पृष्ठ">
                            ›
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="पछिल्लो पृष्ठ">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-bold text-stone-300 bg-white border border-stone-200 cursor-not-allowed rounded-r-xl">
                                ›
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
