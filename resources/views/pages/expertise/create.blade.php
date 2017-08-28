@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-text-right">
                <a href="{{ route('page.document.list') }}" class="uk-button uk-button-primary">К списку экспертиз</a>
            </div>

            <form action="{{ route('page.expertise.store') }}" class="uk-form uk-margin-top uk-margin-large-bottom" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <fieldset>
                    <h3>{{ $title }}</h3>
                    <hr>
                    <div class="uk-form-row">
                        <div class="uk-grid uk-grid-width-1-2">
                            <div>
                                <div>
                                    <label class="uk-form-label">Фабула</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <textarea name="info" class="uk-width-1-1{{ $errors->has('info') ? ' uk-form-danger' : '' }}" rows="7" placeholder="Заполните фабулу">{{ old('info') }}</textarea>
                                    </div>
                                </div>
                                @if ($errors->has('info'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('info') }}</p>
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
                                <div class="uk-margin-top">
                                    <label class="uk-form-label">Категория дела</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <select class="uk-width-1-1{{ ($errors->has('category_id')) ? ' uk-form-danger' : ''}}" name="category_id">
                                            <option value="" {{ (old('category_id') == '') ? 'selected' : '' }} disabled>Выберите категорию дела</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('category_id'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('category_id') }}</p>
                                @endif
                                <div class="uk-margin-top">
                                    <label class="uk-form-label">№ дела</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <input type="text" placeholder="Введите номер дела" class="uk-width-1-1{{ $errors->has('case_number') ? ' uk-form-danger' : '' }}" name="case_number" value="{{ old('case_number') }}">
                                    </div>
                                </div>
                                @if ($errors->has('case_number'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('case_number') }}</p>
                                @endif
                                <div class="uk-margin-top">
                                    <label class="uk-form-label">№ статьи</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <input type="text" placeholder="Введите номер статьи" class="uk-width-1-1{{ $errors->has('article_number') ? ' uk-form-danger' : '' }}" name="article_number" value="{{ old('article_number') }}">
                                    </div>
                                </div>
                                @if ($errors->has('article_number'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('article_number') }}</p>
                                @endif
                                <div class="uk-margin-top">
                                    <label class="uk-form-label">Статус</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <select class="uk-width-1-1{{($errors->has('expertise_status')) ? ' uk-form-danger' : ''}}" name="expertise_status">
                                            <option value="" {{ (old('expertise_status') == '') ? 'selected' : '' }} disabled>Выберите статус</option>
                                            @foreach($statuses[1] as $status)
                                                <option value="{{ $status->id }}" {{ (old('expertise_status') == $status->id) ? 'selected' : '' }}>{{ $status->name }}</option>
                                            @endforeach
                                            <?php unset($status); ?>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('expertise_status'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('expertise_status') }}</p>
                                @endif
                                <div class="uk-margin-top">
                                    <label class="uk-form-label">Дополнительный статус</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <select class="uk-width-1-1{{($errors->has('expertise_additional_status')) ? ' uk-form-danger' : ''}}" name="expertise_additional_status">
                                            <option value="" {{ (old('expertise_additional_status') == '') ? 'selected' : '' }} disabled>Выберите дополнительный статус</option>
                                            @foreach($statuses[0] as $status)
                                                <option value="{{ $status->id }}" {{ (old('expertise_additional_status') == $status->id) ? 'selected' : '' }}>{{ $status->name }}</option>
                                            @endforeach
                                            <?php unset($status); ?>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('expertise_additional_status'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('expertise_additional_status') }}</p>
                                @endif
                                <div class="uk-margin-top uk-position-relative">
                                    <label class="uk-form-label">Шифр экспертизы</label>
                                    <div class="uk-form-controls uk-margin-small-top uk-position-relative">
                                        <input id="speciality-search-input" type="text" placeholder="Введите шифр или название экспертизы" class="uk-width-1-1{{ $errors->has('expertise_speciality_ids') ? ' uk-form-danger' : '' }}" name="" >
                                        <input id="speciality-input" type="hidden" name="expertise_speciality_ids" value="{{old('expertise_speciality_ids')}}">
                                    </div>
                                    <div id="specialities-drop-down" class="drop-down">
                                    </div>
                                    <ul id="specialities-list" class="uk-list">
                                        @if(old('expertise_speciality_ids'))
                                            <?php $specialities = Expertise::specialities(old('expertise_speciality_ids')); ?>
                                            @foreach($specialities as $speciality)
                                                <li><span>{{$speciality->code . ' - ' . $speciality->name}}</span><a href="#" class="speciality-remove" data-id="{{ $speciality->id }}" data-code="{{ $speciality->code }}"><i class="uk-icon-remove"></i></a></li>
                                                <?php unset($speciality); ?>
                                            @endforeach
                                            <?php unset($specialities); ?>
                                        @endif
                                    </ul>
                                </div>
                                @if ($errors->has('expertise_speciality_ids'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('expertise_speciality_ids') }}</p>
                                @endif
                            </div>
                            <div>
                                <div>
                                    <label class="uk-form-label">Регион назначивший экспертизу</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <select class="uk-width-1-1{{($errors->has('expertise_region_id')) ? ' uk-form-danger' : ''}}" name="expertise_region_id" id="expertise-region-select">
                                            <option value="" {{ (old('expertise_region_id') == '') ? 'selected' : '' }} disabled>Выберите регион назначивший экспертизу</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->id }}" {{ (old('expertise_region_id') == $region->id) ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('expertise_region_id'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('expertise_region_id') }}</p>
                                @endif
                                <div class="uk-margin-top">
                                    <label class="uk-form-label">Наименование органа</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <select id="expertise-agency-select" class="uk-width-1-1{{($errors->has('expertise_agency_id')) ? ' uk-form-danger' : ''}}" name="expertise_agency_id" {{ (old('expertise_agency_id') == '') ? 'disabled' : '' }}>
                                            <option value="" {{ (old('expertise_agency_id') == '') ? 'selected' : '' }} disabled>Выберите наименование органа</option>
                                            @if(old('expertise_agency_id'))
                                                <?php $agencies = Expertise::agencies(old('expertise_region_id')); ?>
                                                @foreach($agencies as $agency)
                                                    <option value="{{ $agency->id }}" {{ (old('expertise_agency_id') == $agency->id) ? 'selected' : '' }}>{{ $agency->name }}</option>
                                                    <?php unset($agency); ?>
                                                @endforeach
                                                <?php unset($agencies); ?>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('expertise_agency_id'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('expertise_agency_id') }}</p>
                                @endif
                                <div class="uk-margin-top">
                                    <label class="uk-form-label">Орган назначивший экспертизу</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <select id="expertise-organ-select" class="uk-width-1-1{{($errors->has('expertise_organ_id')) ? ' uk-form-danger' : ''}}" name="expertise_organ_id">
                                            <option value="" {{ (old('expertise_organ_id') == '') ? 'selected' : '' }} disabled>Выберите орган назначивший экспертизу</option>
                                            @foreach($organs as $organ)
                                                <option value="{{ $organ->id }}" {{ (old('expertise_organ_id') == $organ->id) ? 'selected' : '' }}>{{ $organ->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('expertise_organ_id'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('expertise_organ_id') }}</p>
                                @endif
                                <div class="uk-margin-top{{ (old('expertise_organ_id') == 1) ? '' : ' uk-hidden' }}" id="expertise-organ-name">
                                    <label class="uk-form-label">Введите название органа</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <input type="text" placeholder="Введите номер дела" class="uk-width-1-1{{ $errors->has('expertise_organ_name') ? ' uk-form-danger' : '' }}" name="expertise_organ_name" value="{{ old('expertise_organ_name') }}">
                                    </div>
                                </div>
                                <div class="uk-margin-top">
                                    <span class="uk-h4">Данные лица назначившего экспертизу</span>
                                </div>
                                <div class="uk-margin-top expertise-extra-fields" id="expertise-user-fullname">
                                    <label class="uk-form-label">ФИО</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <input type="text" placeholder="ФИО лица назначившего экспертизу" class="uk-width-1-1{{ $errors->has('expertise_user_fullname') ? ' uk-form-danger' : '' }}" name="expertise_user_fullname" value="{{ old('expertise_user_fullname') }}">
                                    </div>
                                </div>
                                @if ($errors->has('expertise_user_fullname'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('expertise_user_fullname') }}</p>
                                @endif
                                <div class="uk-margin-top expertise-extra-fields" id="expertise-user-position">
                                    <label class="uk-form-label">Должность</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <input type="text" placeholder="Должность лица назначившего экспертизу" class="uk-width-1-1{{ $errors->has('expertise_user_position') ? ' uk-form-danger' : '' }}" name="expertise_user_position" value="{{ old('expertise_user_position') }}">
                                    </div>
                                </div>
                                <div class="uk-margin-top expertise-extra-fields" id="expertise-user-rank">
                                    <label class="uk-form-label">Звание</label>
                                    <div class="uk-form-controls uk-margin-small-top">
                                        <select class="uk-width-1-1" name="expertise_user_rank">
                                            <option value="" {{ (old('expertise_user_rank') == '') ? 'selected' : '' }} disabled>Выберите звание лица назначившего экспертизу</option>
                                            <option value="Лейтенант" {{ (old('expertise_user_rank') == 'Лейтенант') ? 'selected' : '' }}>Лейтенант</option>
                                            <option value="Cтарший лейтенант" {{ (old('expertise_user_rank') == 'Cтарший лейтенант') ? 'selected' : '' }}>Cтарший лейтенант</option>
                                            <option value="Капитан" {{ (old('expertise_user_rank') == 'Капитан') ? 'selected' : '' }}>Капитан</option>
                                            <option value="Майор" {{ (old('expertise_user_rank') == 'Майор') ? 'selected' : '' }}>Майор</option>
                                            <option value="Подполковник" {{ (old('expertise_user_rank') == 'Подполковник') ? 'selected' : '' }}>Подполковник</option>
                                            <option value="Полковник" {{ (old('expertise_user_rank') == 'Полковник') ? 'selected' : '' }}>Полковник</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="uk-text-right">
                        <button type="submit" class="uk-button uk-button-success">Создать экспертизу</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection