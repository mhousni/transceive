<nav aria-label="breadcrumb">
    {{-- <ol class="breadcrumb breadcrumb-sa-simple mb-0">
        <?php $segments = ''; ?>
        @foreach (request()->segments() as $segment)
            <?php $segments .= '/' . $segment; ?>
            @if ($segment != LaravelLocalization::getCurrentLocale())
                <li class="breadcrumb-item  @if(request()->segment(count(request()->segments())) == $segment) active @endif">
                    @if(request()->segment(count(request()->segments())) != $segment)
                    <a href="{{ url($segments) }}">{{ $segment }}</a>
                @else
                    {{ $segment }}
            @endif
            </li>
        @endif
        @endforeach
    </ol> --}}
</nav>
