@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">

        {{-- mobile --}}
        <div class="justify-between flex-1 hidden smaller-than-740:flex">
            @if ($paginator->onFirstPage())
                <span class="pagination-link-inactive rounded-md min-w-[96px]">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link-normal rounded-md min-w-[96px]">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link-normal rounded-md min-w-[96px]">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="pagination-link-inactive rounded-md min-w-[96px]">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- desktop --}}
        <div class="flex-1 flex items-center justify-between smaller-than-928:justify-end smaller-than-740:hidden">
            <div class="smaller-than-928:hidden">
                <p class="text-sm text-gray-400 leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative inline-flex shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="pagination-link-inactive rounded-l-md" aria-hidden="true">
                                <x-icon.arrow :direction="'left'" class="w-5 h-5" />
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            aria-label="{{ __('pagination.previous') }}" class="pagination-link-normal rounded-l-md">
                            <x-icon.arrow :direction="'left'" class="w-5 h-5" />
                        </a>
                    @endif
                    
                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="pagination-link-inactive">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="pagination-link-active">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                        class="pagination-link-normal">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
                            class="pagination-link-normal rounded-r-md">
                            <x-icon.arrow :direction="'right'" class="w-5 h-5" />
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="pagination-link-inactive rounded-r-md" aria-hidden="true">
                                <x-icon.arrow :direction="'right'" class="w-5 h-5" />
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
