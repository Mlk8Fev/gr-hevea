@props(['paginator'])

@if($paginator->hasPages())
<nav aria-label="Navigation des pages">
    <ul class="pagination pagination-sm mb-0">
        {{-- Page précédente --}}
        @if($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link bg-light border-0 text-muted">
                    Précédent
                </span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $paginator->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                    Précédent
                </a>
            </li>
        @endif

        {{-- Pages intelligentes --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $startPage = max(1, $currentPage - 2);
            $endPage = min($lastPage, $currentPage + 2);
        @endphp

        {{-- Première page --}}
        @if($startPage > 1)
            <li class="page-item">
                <a href="{{ $paginator->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
            </li>
            @if($startPage > 2)
                <li class="page-item disabled">
                    <span class="page-link bg-light border-0 text-muted">...</span>
                </li>
            @endif
        @endif

        {{-- Pages autour de la page courante --}}
        @for($page = $startPage; $page <= $endPage; $page++)
            @if($page == $currentPage)
                <li class="page-item active">
                    <span class="page-link bg-primary border-0 text-white fw-semibold">{{ $page }}</span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $page }}</a>
                </li>
            @endif
        @endfor

        {{-- Dernière page --}}
        @if($endPage < $lastPage)
            @if($endPage < $lastPage - 1)
                <li class="page-item disabled">
                    <span class="page-link bg-light border-0 text-muted">...</span>
                </li>
            @endif
            <li class="page-item">
                <a href="{{ $paginator->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $lastPage }}</a>
            </li>
        @endif

        {{-- Page suivante --}}
        @if($paginator->hasMorePages())
            <li class="page-item">
                <a href="{{ $paginator->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                    Suivant
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link bg-light border-0 text-muted">
                    Suivant
                </span>
            </li>
        @endif
    </ul>
</nav>
@endif 