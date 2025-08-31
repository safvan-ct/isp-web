@props(['id' => 'pagination-nav'])

@if ($paginator->lastPage() > 1)
    <nav id="{{ $id }}" aria-label="Pagination" class="mt-2">
        <ul class="pagination justify-content-center mb-1" id="{{ $id }}-list">
            {{-- Previous --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="javascript:void(0);"
                    data-page="{{ $paginator->currentPage() - 1 }}">Previous</a>
            </li>

            {{-- Page numbers with ellipsis --}}
            @php
                $delta = 3;
                $range = [];
                for ($i = 1; $i <= $paginator->lastPage(); $i++) {
                    if (
                        $i == 1 ||
                        $i == $paginator->lastPage() ||
                        ($i >= $paginator->currentPage() - $delta && $i <= $paginator->currentPage() + $delta)
                    ) {
                        $range[] = $i;
                    }
                }
                $lastPage = 0;
            @endphp

            @foreach ($range as $page)
                @if ($page - $lastPage > 1)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif

                <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="javascript:void(0);"
                        data-page="{{ $page }}">{{ $page }}</a>
                </li>
                @php $lastPage = $page; @endphp
            @endforeach

            {{-- Next --}}
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="javascript:void(0);" data-page="{{ $paginator->currentPage() + 1 }}">Next</a>
            </li>
        </ul>
    </nav>
@endif
