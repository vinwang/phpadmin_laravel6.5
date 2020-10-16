@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            <span class="page-item">共 {{ $paginator->total() }} 条</span>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
            <li class="page-item">
                <form class="layui-form" action="{{ $paginator->url($paginator->currentPage()) }}" method="get">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                        <label class="layui-form-label" style="width:auto;">到第</label>
                        <div class="layui-input-inline" style="width:40px;">
                            <input type="text" min="1" name="page" class="layui-input">
                        </div>
                        <label class="layui-form-label" style="width:auto;padding-left:0;padding-right:0;">页</label>
                        </div>
                        
                        <div class="layui-inline">
                        <button type="submit" class="layui-btn layui-btn-primary">确定</button>
                        </div>
                    </div>
                </form>
            </li>
        </ul>
    </nav>
    
@endif
