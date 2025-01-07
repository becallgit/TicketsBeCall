<div class="pagination">
    @foreach ($paginator->links() as $link)
        @if ($link->active)
            <a href="{{ $link->url }}" class="page">{{ $link->label }}</a>
        @endif
    @endforeach
</div>
