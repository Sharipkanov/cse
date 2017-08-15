@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <form action="{{ route('page.user.store') }}" class="uk-form uk-margin-top uk-margin-large-bottom" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <fieldset>
                    <h3>{{ $title }}</h3>
                    <hr>
                    <div class="uk-grid uk-grid-width-1-2">
                        <div>
                            <div>
                                <label class="uk-form-label">Фамилия:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" class="uk-width-1-1{{ $errors->has('last_name') ? ' uk-form-danger' : '' }}" name="last_name">
                                </div>
                            </div>
                            @if ($errors->has('last_name'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('last_name') }}</p>
                            @endif

                            <div class="uk-margin-top">
                                <label class="uk-form-label">Имя:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" class="uk-width-1-1{{ $errors->has('first_name') ? ' uk-form-danger' : '' }}" name="first_name">
                                </div>
                            </div>
                            @if ($errors->has('first_name'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('first_name') }}</p>
                            @endif

                            <div class="uk-margin-top">
                                <label class="uk-form-label">Отчество:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" class="uk-width-1-1{{ $errors->has('middle_name') ? ' uk-form-danger' : '' }}" name="middle_name">
                                </div>
                            </div>
                            @if ($errors->has('middle_name'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('middle_name') }}</p>
                            @endif

                            <div class="uk-margin-top">
                                <label class="uk-form-label">Электронная почта:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" class="uk-width-1-1{{ $errors->has('email') ? ' uk-form-danger' : '' }}" name="email">
                                </div>
                            </div>
                            @if ($errors->has('email'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                        <div>
                            <div>
                                <label class="uk-form-label">Регион назначивший экспертизу:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select class="uk-width-1-1{{($errors->has('department_id')) ? ' uk-form-danger' : ''}}" name="department_id" id="expertise-region-select">
                                        <option value="" {{ (old('department_id') == '') ? 'selected' : '' }} disabled>Выберите отдел</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ (old('department_id') == $department->id) ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('department_id'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('department_id') }}</p>
                            @endif
                            <div class="uk-margin-top">
                                <label class="uk-form-label">Наименование органа:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select id="expertise-agency-select" class="uk-width-1-1{{($errors->has('subdivision_id')) ? ' uk-form-danger' : ''}}" name="subdivision_id">
                                        <option value="">Выберите пододел</option>
                                        @foreach($departments as $department)
                                            <option value="" disabled>{{ $department->name }}</option>
                                            @foreach($department->subdivisions() as $subdivision)
                                                <option value="{{ $subdivision->id }}">-{{ $subdivision->name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('subdivision_id'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('subdivision_id') }}</p>
                            @endif

                            <div class="uk-margin-top">
                                <label class="uk-form-label">Должность:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select name="position_id" class="uk-width-1-1{{ $errors->has('position_id') ? ' uk-form-danger' : '' }}">
                                        <option value="">Выберите должность</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('position_id'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('position_id') }}</p>
                            @endif
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