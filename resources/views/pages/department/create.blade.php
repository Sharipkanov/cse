@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    @if(session()->has('success'))
        <div class="uk-width-1-1 uk-margin-top">
            <div class="uk-container uk-container-center">
                <span class="h4 uk-text-success">Пользоватедь успешно создан</span>
            </div>
        </div>
    @endif
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <form action="{{ ($departments) ? route('page.subdivision.store') : route('page.department.store') }}" class="uk-form uk-margin-large-bottom" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <fieldset>
                    <h3>{{ $title }}</h3>
                    <hr>
                    <div>
                        <div>
                            <label class="uk-form-label">Название {{ ($departments) ? 'подотдела' : 'отдела' }}:</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input type="text" class="uk-width-1-1{{ $errors->has('name') ? ' uk-form-danger' : '' }}" name="name" value="{{ old('name') }}">
                            </div>
                        </div>
                        @if ($errors->has('name'))
                            <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                    @if($departments)
                        <div class="uk-margin-top">
                            <div>
                                <label class="uk-form-label">Выберите отдел:</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select name="department_id" class="uk-width-1-1{{ $errors->has('department_id') ? ' uk-form-danger' : '' }}">
                                        <option value="">Выберите отдел</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ (old('department_id') == $department->id) ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('department_id'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('department_id') }}</p>
                            @endif
                        </div>
                    @endif
                    <div class="uk-text-right uk-margin-top">
                        <button type="submit" class="uk-button uk-button-success">Создать {{ ($departments) ? 'подотдел' : 'отдел' }}</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection