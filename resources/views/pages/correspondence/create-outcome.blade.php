@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <form action="{{ route('page.correspondence.store.outcome') }}" class="uk-form uk-margin-top uk-margin-large-bottom" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <fieldset>
                    <h3>{{ $title }}</h3>
                    <hr>
                    <div>
                        <div>
                            <label class="uk-form-label">Язык обращения:</label>
                            <div class="uk-form-controls uk-margin-small-top uk-flex">
                                @foreach($languages as $language)
                                    <div class="{{ ($loop->first) ? 'uk-margin-right' : ''}}">
                                        <label class="uk-flex uk-flex-middle">
                                    <span class="uk-margin-small-right">
                                        <input type="radio" name="language_id" value="{{ $language->id }}" {{ (old('language_id') == $language->id) ? 'checked' : '' }}>
                                    </span>
                                            <span>{{ $language->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($errors->has('language_id'))
                            <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('language_id') }}</p>
                        @endif

                        @if($correspondence)
                            <div class="uk-margin-top">
                                <label class="uk-form-label">Корреспондент:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="hidden" name="correspondent_id" value="{{ $correspondence->correspondent()->id }}">
                                    <input name="" class="uk-width-1-1" value="{{ $correspondence->correspondent()->name }}" readonly>
                                </div>
                            </div>
                            <div class="uk-margin-top">
                                <label class="uk-form-label">Ответ на входящий:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="hidden" name="reply_correspondence_id" value="{{ $correspondence->id }}">
                                    <input name="" class="uk-width-1-1" value="{{ $correspondence->register_number }}" readonly>
                                </div>
                            </div>
                        @else
                            <div class="uk-margin-top uk-position-relative">
                                <label class="uk-form-label">Корреспондент:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" name="" id="correspondent-search-input" placeholder="Введите имя корреспондента" class="uk-width-1-1{{ ($errors->has('correspondent_id')) ? ' uk-form-danger' : '' }}">
                                    <input type="hidden" name="correspondent_id"  value="" id="correspondent-input">
                                </div>
                                <div class="drop-down" id="correspondent-drop-down">

                                </div>
                            </div>
                            @if ($errors->has('correspondent_id'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('correspondent_id') }}</p>
                            @endif
                        @endif

                        <div class="uk-margin-top">
                            <label class="uk-form-label">Страницы:</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input name="pages" class="uk-width-1-1{{ $errors->has('pages') ? ' uk-form-danger' : '' }}" value="{{ old('info') }}" placeholder="Введите количество страниц">
                            </div>
                        </div>
                        @if ($errors->has('pages'))
                            <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('pages') }}</p>
                        @endif

                        <div class="uk-margin-top">
                            <label class="uk-form-label">ФИО исполнителя:</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input name="executor_fullname" class="uk-width-1-1" value="{{ $document->author()->last_name .' '. $document->author()->first_name .' '. $document->author()->middle_name}}" readonly>
                            </div>
                        </div>
                        <div class="uk-margin-top">
                            <label class="uk-form-label">Тип документа</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input type="text" value="{{$document->type()->name}}" readonly name="" class="uk-width-1-1">
                                <input type="hidden" name="document_type_id" value="{{ $document->type()->id }}">
                                <input type="hidden" name="document_id" value="{{ $document->id }}">
                            </div>
                        </div>
                        <div class="uk-margin-top">
                            <label class="uk-form-label">Основание</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <a href="{{ route('page.document.show', ['document' => $document->id]) }}">Просмотреть</a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-text-right uk-margin-top">
                        <button type="submit" class="uk-button uk-button-success">Создать карточку</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@endsection