<div class="uk-width-1-1">
    <nav class="uk-navbar">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-space-between uk-flex-middle">
                <ul class="uk-navbar-nav">
                    @foreach($navigation as $item)
                        <li class="{{ ($item->current) ? 'uk-active' : '' }}"><a href="{{ $item->url }}">{{ $item->name }}</a></li>
                    @endforeach
                </ul>
                <div>
                    <button class="uk-button" data-uk-toggle="{target:'#add-correspondent', animation:'uk-animation-slide-right, uk-animation-slide-right'}">Добавить корреспондента</button>
                </div>
            </div>
        </div>
    </nav>
</div>