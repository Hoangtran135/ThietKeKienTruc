@if ($paginator->hasPages())
<nav aria-label="Pagination" style="margin-top:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">

        {{-- Result count --}}
        <div style="font-size:13px;color:var(--gray-700);">
            Hiển thị <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
            trong <strong>{{ $paginator->total() }}</strong> kết quả
        </div>

        {{-- Page links --}}
        <ul style="display:flex;gap:4px;list-style:none;margin:0;padding:0;flex-wrap:wrap;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span style="display:inline-flex;align-items:center;padding:6px 12px;border:1.5px solid var(--gray-200);border-radius:7px;font-size:13px;color:var(--gray-500);cursor:not-allowed;background:#fff;">
                        &#8592; Trước
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       style="display:inline-flex;align-items:center;padding:6px 12px;border:1.5px solid var(--gray-200);border-radius:7px;font-size:13px;color:var(--gray-700);background:#fff;text-decoration:none;transition:all .2s;"
                       onmouseover="this.style.background='var(--red)';this.style.color='#fff';this.style.borderColor='var(--red)'"
                       onmouseout="this.style.background='#fff';this.style.color='var(--gray-700)';this.style.borderColor='var(--gray-200)'">
                        &#8592; Trước
                    </a>
                </li>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span style="display:inline-flex;align-items:center;padding:6px 10px;font-size:13px;color:var(--gray-500);">...</span>
                    </li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span style="display:inline-flex;align-items:center;padding:6px 12px;border:1.5px solid var(--red);border-radius:7px;font-size:13px;font-weight:700;background:var(--red);color:#fff;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                   style="display:inline-flex;align-items:center;padding:6px 12px;border:1.5px solid var(--gray-200);border-radius:7px;font-size:13px;color:var(--gray-700);background:#fff;text-decoration:none;transition:all .2s;"
                                   onmouseover="this.style.background='var(--red)';this.style.color='#fff';this.style.borderColor='var(--red)'"
                                   onmouseout="this.style.background='#fff';this.style.color='var(--gray-700)';this.style.borderColor='var(--gray-200)'">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                       style="display:inline-flex;align-items:center;padding:6px 12px;border:1.5px solid var(--gray-200);border-radius:7px;font-size:13px;color:var(--gray-700);background:#fff;text-decoration:none;transition:all .2s;"
                       onmouseover="this.style.background='var(--red)';this.style.color='#fff';this.style.borderColor='var(--red)'"
                       onmouseout="this.style.background='#fff';this.style.color='var(--gray-700)';this.style.borderColor='var(--gray-200)'">
                        Tiếp &#8594;
                    </a>
                </li>
            @else
                <li>
                    <span style="display:inline-flex;align-items:center;padding:6px 12px;border:1.5px solid var(--gray-200);border-radius:7px;font-size:13px;color:var(--gray-500);cursor:not-allowed;background:#fff;">
                        Tiếp &#8594;
                    </span>
                </li>
            @endif

        </ul>
    </div>
</nav>
@endif
