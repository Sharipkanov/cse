@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')

    <div class="uk-width-1-1 uk-margin-large-top">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-width-1-3 uk-grid-match" data-uk-grid-match="{target:'.uk-panel'}" data-uk-grid-margin>
                <div>
                    <a href="{{ route('page.correspondence.list', ['type' => 'income']) }}" class="uk-panel uk-panel-box uk-flex uk-flex-middle uk-flex-center">
                        <span class="uk-h3">Корреспонденция</span>
                    </a>
                </div>
                <div>
                    <a href="{{ route('page.document.list') }}" class="uk-panel uk-panel-box uk-flex uk-flex-middle uk-flex-center">
                        <span class="uk-h3">Документы</span>
                    </a>
                </div>
                <div>
                    <a href="{{ route('page.expertise.list') }}" class="uk-panel uk-panel-box uk-flex uk-flex-middle uk-flex-center">
                        <span class="uk-h3">Экспертизы</span>
                    </a>
                </div>
                <div>
                    <a href="{{ route('page.task.list') }}" class="uk-panel uk-panel-box uk-flex uk-flex-middle uk-flex-center">
                        <span class="uk-h3">Контроль</span>
                    </a>
                </div>
                <div>
                    <a href="{{ route('page.structure') }}" class="uk-panel uk-panel-box uk-flex uk-flex-middle uk-flex-center">
                        <span class="uk-h3">Структура предприятия</span>
                    </a>
                </div>
                <div>
                    <a href="https://mail.yandex.kz/" target="_blank" class="uk-panel uk-panel-box uk-flex uk-flex-middle uk-flex-center">
                        <span class="uk-h3">Почта</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection