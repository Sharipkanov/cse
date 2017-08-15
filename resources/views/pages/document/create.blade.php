@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-text-right">
                <a href="{{ route('page.document.list') }}" class="uk-button uk-button-primary">К списку документов</a>
            </div>

            <form action="{{ route('page.document.store') }}" class="uk-form uk-margin-top uk-margin-large-bottom" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <fieldset>
                    <h3>{{ $title }}</h3>
                    <hr>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Тема документа</label>
                        <div class="uk-form-controls uk-margin-small-top">
                            <input type="text" placeholder="Введите тему документа" class="uk-width-1-1{{ $errors->has('name') ? ' uk-form-danger' : '' }}" name="name" value="{{ old('name') }}">
                        </div>
                    </div>
                    @if ($errors->has('name'))
                        <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('name') }}</p>
                    @endif
                    <div class="uk-form-row">
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
                    <div class="uk-form-row">
                        <div class="uk-grid uk-grid-width-1-2">
                            <div>
                                <label class="uk-form-label">Номенклатурное дело</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select class="uk-width-1-1" name="nomenclature_id" data-switcher-select="nomenclature-code">
                                        @foreach($nomenclatures as $nomenclature)
                                            <option value="{{ $nomenclature->id }}" {{ (old('nomenclature_id') == $nomenclature->id) ? 'selected' : '' }}>{{ $nomenclature->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="uk-form-label">Номенклатурный код</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select class="uk-width-1-1" id="nomenclature-code" data-disabled-select>
                                        @foreach($nomenclatures as $nomenclature)
                                            <option value="{{ $nomenclature->id }}" {{ (old('nomenclature_id') == $nomenclature->id) ? 'selected' : '' }}>{{ $nomenclature->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('nomenclature_id'))
                        <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('nomenclature_id') }}</p>
                    @endif
                    <div class="uk-form-row">
                        <label class="uk-form-label">Дополнительная информация</label>
                        <div class="uk-form-controls uk-margin-small-top">
                            <textarea name="info" class="uk-width-1-1{{ $errors->has('info') ? ' uk-form-danger' : '' }}" rows="9" placeholder="Введите дополнительная информация">{{ old('info') }}</textarea>
                        </div>
                    </div>
                    @if ($errors->has('info'))
                        <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('info') }}</p>
                    @endif
                    <div class="uk-form-row">
                        <label class="uk-form-label">Приложения</label>
                        <div class="uk-form-controls uk-margin-small-top">
                            <input type="file" name="files[]" multiple>
                        </div>
                    </div>
                    @if ($errors->has('files'))
                        <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('files') }}</p>
                    @endif
                    <hr>
                    <div class="uk-text-right">
                        <button type="submit" class="uk-button uk-button-success">Создать документ</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection