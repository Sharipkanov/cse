<div class="uk-width-1-1">
    <nav class="uk-navbar">
        <div class="uk-container uk-container-center">
            <ul class="uk-navbar-nav">
                @foreach($navigation as $item)
                    <li class="{{ ($item->current) ? 'uk-active' : '' }}"><a href="{{ $item->url }}">{{ $item->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </nav>
</div>