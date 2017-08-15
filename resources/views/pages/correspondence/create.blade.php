@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <form action="{{ route('page.correspondence.store') }}" class="uk-form uk-margin-top uk-margin-large-bottom" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <fieldset>
                    <h3>{{ $title }}</h3>
                    <hr>
                    <div class="uk-grid uk-grid-width-1-2">
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

                            <div class="uk-margin-top">
                                <label class="uk-form-label">ФИО исполнителя:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input name="executor_fullname" class="uk-width-1-1{{ $errors->has('executor_fullname') ? ' uk-form-danger' : '' }}" value="{{ old('info') }}" placeholder="Введите ФИО исполнителя">
                                </div>
                            </div>
                            @if ($errors->has('executor_fullname'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('executor_fullname') }}</p>
                            @endif

                            <div class="uk-margin-top">
                                <label class="uk-form-label">Исходящий №:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input name="outcome_number" class="uk-width-1-1{{ $errors->has('outcome_number') ? ' uk-form-danger' : '' }}" value="{{ old('info') }}" placeholder="Введите исходящий номер">
                                </div>
                            </div>
                            @if ($errors->has('outcome_number'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('outcome_number') }}</p>
                            @endif

                            <div class="uk-margin-top">
                                <label class="uk-form-label">Дата исходящего:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" class="uk-width-1-1{{ $errors->has('outcome_date') ? ' uk-form-danger' : '' }}" name="outcome_date" placeholder="Выберите дату исходящего" name="date" data-uk-datepicker="{format:'YYYY-MM-DD', i18n: {months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], weekdays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']}}">
                                </div>
                            </div>
                            @if ($errors->has('outcome_date'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('outcome_date') }}</p>
                            @endif

                            <div class="uk-margin-top">
                                <label class="uk-form-label">Срок исполнения:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" class="uk-width-1-1" name="execution_period" placeholder="Выберите cрок исполнения" data-uk-datepicker="{format:'YYYY-MM-DD', i18n: {months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], weekdays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']}}">
                                </div>
                            </div>
                            @if ($errors->has('execution_period'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('execution_period') }}</p>
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
                                <label class="uk-form-label">Приложения</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="file" name="files[]" multiple>
                                </div>
                            </div>
                            @if ($errors->has('files'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('files') }}</p>
                            @endif
                        </div>
                        <div>
                            <div class="uk-position-relative">
                                <label class="uk-form-label">Ответ на исходящий:</label>
                                <div class="uk-form-controls uk-margin-small-top uk-position-relative">
                                    <input id="correspondence-search-input" type="text" placeholder="Введите шифр или название экспертизы" class="uk-width-1-1{{ $errors->has('reply_correspondence_id') ? ' uk-form-danger' : '' }}" >
                                    <input type="hidden" name="reply_correspondence_id">
                                </div>
                                <div id="correspondence-drop-down" class="drop-down">
                                </div>
                                <ul id="specialities-list" class="uk-list">
                                </ul>
                            </div>
                            <div class="uk-margin-top ">
                                <label class="uk-form-label">Получатель:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" value="Бекжанов Ж.Л" disabled class="uk-width-1-1">
                                </div>
                            </div>
                            <div class="uk-margin-top">
                                <label class="uk-form-label">Тип документа</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select class="uk-width-1-1{{ $errors->has('document_type_id') ? ' uk-form-danger' : '' }}" name="document_type_id">
                                        @foreach($documentTypes as $documentType)
                                            <option value="{{ $documentType->id }}" {{ (old('document_type_id') == $documentType->id) ? 'selected' : '' }}>{{ $documentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('document_type_id'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('document_type_id') }}</p>
                            @endif
                            <input type="hidden" name="is_income" value="1">
                        </div>
                    </div>
                    <hr>
                    <div class="uk-text-right">
                        <button type="submit" class="uk-button uk-button-success">Создать карточку</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection