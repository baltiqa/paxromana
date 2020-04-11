@if ($paginator->hasPages())
<nav style="margin-top:15px;" class="mp-PaginationControls-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button disabled="" class="mp-Button mp-Button--round mp-Button--md">
						<span class="mp-Button-icon mp-Button-icon--center mp-svg-arrow-left-white"></span>
			</button>
        @else
        <button class="mp-Button mp-Button--round mp-Button--md">
          <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><span class="mp-Button-icon mp-Button-icon--center mp-svg-arrow-left-white"></span></a>
		</button>
        @endif
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="mp-PaginationControls-pagination-pageList"><span>{{ $page }}</span></span>
                    @else
                        <span class="mp-PaginationControls-pagination-pageList"><span><a class="page-link" href="{{ $url }}">{{ $page }}</a></span></span>
                    @endif
                @endforeach
            @endif
        @endforeach
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <button class="mp-Button mp-Button--round mp-Button--md">    
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><span  class="mp-Button-icon mp-Button-icon--center mp-svg-arrow-right-white"></span></a>
         </button>
        @else
        <button disabled="" class="mp-Button mp-Button--round mp-Button--md">    
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><span  class="mp-Button-icon mp-Button-icon--center mp-svg-arrow-right-white"></span></a>
         </button>

        @endif
</nav>
@endif

					
