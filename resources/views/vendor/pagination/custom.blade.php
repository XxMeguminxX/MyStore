@if ($paginator->hasPages())
<div class="pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <button class="page-btn arrow" disabled style="opacity:0.4;cursor:default;">← Sebelumnya</button>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-btn arrow">← Sebelumnya</a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <span class="page-dots">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <button class="page-btn active">{{ $page }}</button>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-btn arrow">Berikutnya →</a>
    @else
        <button class="page-btn arrow" disabled style="opacity:0.4;cursor:default;">Berikutnya →</button>
    @endif
</div>
@endif
