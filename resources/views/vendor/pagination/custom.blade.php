@if ($paginator->hasPages())
    <ul class="pagination pagination-sm mb-0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">Prev</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Prev</a>
            </li>
        @endif

        {{-- First Page Link --}}
        @if ($paginator->currentPage() > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url(1) }}">First</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">First</span>
            </li>
        @endif

        {{-- Pagination Elements (Window of 5) --}}
        @php
            $windowSize = 5;
            $half = floor($windowSize / 2);
            $startPage = $paginator->currentPage() - $half;
            $endPage = $paginator->currentPage() + $half;
            $totalPages = $paginator->lastPage();

            if ($startPage < 1) {
                $startPage = 1;
                $endPage = min($windowSize, $totalPages);
            }

            if ($endPage > $totalPages) {
                $endPage = $totalPages;
                $startPage = max(1, $totalPages - $windowSize + 1);
            }
        @endphp

        @for ($i = $startPage; $i <= $endPage; $i++)
            @if ($i == $paginator->currentPage())
                <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor

        {{-- Last Page Link --}}
        @if ($paginator->currentPage() < $paginator->lastPage())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">Last</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">Last</span>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
@endif
