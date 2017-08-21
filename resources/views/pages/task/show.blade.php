@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="uk-margin-top uk-flex uk-flex-middle">
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status warning uk-margin-small-right"></div><small>На исполнении</small></div>
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status success uk-margin-small-right"></div><small>Исполнено</small></div>
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status danger uk-margin-small-right"></div><small>Просрочено</small></div>
                </div>
                <div>
                    @if(count($users) && !$item_assigned && $item->executor_id == auth()->user()->id && !$item->status)
                        <button class="uk-button uk-button-primary" data-uk-toggle="{target:'#task-toggle',  animation:'uk-animation-slide-right, uk-animation-slide-right'}">Переназначить карточку задания</button>
                    @endif
                </div>
            </div>
            @if(count($users) && !$item_assigned && !$item->status)
                <div id="task-toggle" class="uk-margin-top {{ ($errors->any()) ? '' : ' uk-hidden' }}">
                    <form action="{{ route('page.task.edit', ['task' => $item->id]) }}" class="uk-form" method="post">
                        <input type="hidden" name="parent_id" value="{{ $item->id }}">
                        {{ csrf_field() }}
                        @if($users)
                            <div class="uk-form-row">
                                <label class="uk-form-label">Укажите срок исполнения</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select name="executor_id" class="uk-width-1-1">
                                        <option value="">Выберите исполнителя</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ (old('executor_id') == $user->id) ? 'selected' : ''}}>{{ $user->last_name }} {{ $user->first_name }} {{ $user->middle_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        @if ($errors->has('executor_id'))
                            <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('executor_id') }}</p>
                        @endif
                        <div class="uk-form-row">
                            <label class="uk-form-label">Укажите срок исполнения</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input type="text" value="{{ old('execution_date') }}" class="uk-width-1-1" name="execution_date" placeholder="Выберите cрок исполнения" data-uk-datepicker="{minDate: '{{ date('Y-m-d') }}', maxDate: '{{explode(' ', $item->execution_period)[0]}}', format:'YYYY-MM-DD', i18n: {months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], weekdays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']}}">
                            </div>
                        </div>
                        @if ($errors->has('execution_date'))
                            <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('execution_date') }}</p>
                        @endif
                        <div class="uk-form-row">
                            <label class="uk-form-label">Укажите время исполнения</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input type="text"  value="{{ old('execution_time') }}" class="uk-width-1-1" name="execution_time" placeholder="Выберите время исполнения" data-uk-timepicker="{start: 9, end: 18}">
                            </div>
                        </div>
                        @if ($errors->has('execution_time'))
                            <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('execution_time') }}</p>
                        @endif
                        <div class="uk-form-row">
                            <label class="uk-form-label">Информация задания</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <textarea name="info" rows="10" class="uk-width-1-1" placeholder="Введите текст задания">{{ old('info') }}</textarea>
                            </div>
                        </div>
                        @if ($errors->has('info'))
                            <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('info') }}</p>
                        @endif
                        <div class="uk-form-row uk-text-right">
                            <button type="submit" class="uk-button uk-button-success">Переназначить</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="uk-margin-top uk-margin-bottom">
                <div class="uk-form">
                    <h3>Каточка задания № {{ $item->register_number }}</h3>
                    <hr>
                    <div class="uk-grid uk-grid-width-1-2">
                        <div>
                            <div>
                                <div class="uk-grid">
                                    <div class="uk-width-2-6">
                                        <p class="uk-text-bold">Автор</p>
                                    </div>
                                    <div class="uk-width-4-6">
                                        <p>{{ $item->author()->last_name }} {{ $item->author()->first_name }} {{ $item->author()->middle_name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-margin-top">
                                <div class="uk-grid">
                                    <div class="uk-width-2-6">
                                        <p class="uk-text-bold">ФИО исполнителя:</p>
                                    </div>
                                    <div class="uk-width-4-6">
                                        <p>{{ $item->executor()->last_name }} {{ $item->executor()->first_name }} {{ $item->executor()->middle_name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-margin-top">
                                <div class="uk-grid">
                                    <div class="uk-width-2-6">
                                        <p class="uk-text-bold">Срок исполнения:</p>
                                    </div>
                                    <div class="uk-width-4-6">
                                        <p>{{ $item->execution_period }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-margin-top">
                                <div class="uk-grid">
                                    <div class="uk-width-2-6">
                                        <p class="uk-text-bold">Статус:</p>
                                    </div>
                                    <div class="uk-width-4-6">
                                        @if($item->status == 2)
                                            <div class="status danger"></div>
                                        @elseif($item->status == 1)
                                            <div class="status success"></div>
                                        @elseif($item->status == 0)
                                            <div class="status warning"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <div class="uk-grid">
                                    <div class="uk-width-2-6">
                                        <p class="uk-text-bold">Основание:</p>
                                    </div>
                                    <div class="uk-width-4-6">
                                        <p>
                                            @if($item->correspondence_id)
                                                <a href="{{ route('page.correspondence.show', ['correspondence' => $item->correspondence_id]) }}" target="_blank">Просмотреть</a>
                                            @else
                                                Не имеется
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-margin-top">
                                <div class="uk-grid">
                                    <div class="uk-width-2-6">
                                        <p class="uk-text-bold">Информация задания</p>
                                    </div>
                                    <div class="uk-width-4-6">
                                        <p>{{ $item->info }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($item_assigned)
                <div class="uk-margin-top uk-margin-bottom">
                    <div class="uk-form">
                        <h3>Каточка задания № {{ $item_assigned->register_number }}</h3>
                        <hr>
                        <div class="uk-grid uk-grid-width-1-2">
                            <div>
                                <div>
                                    <div class="uk-grid">
                                        <div class="uk-width-2-6">
                                            <p class="uk-text-bold">Автор</p>
                                        </div>
                                        <div class="uk-width-4-6">
                                            <p>{{ $item_assigned->author()->last_name }} {{ $item_assigned->author()->first_name }} {{ $item_assigned->author()->middle_name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-margin-top">
                                    <div class="uk-grid">
                                        <div class="uk-width-2-6">
                                            <p class="uk-text-bold">ФИО исполнителя:</p>
                                        </div>
                                        <div class="uk-width-4-6">
                                            <p>{{ $item_assigned->executor()->last_name }} {{ $item_assigned->executor()->first_name }} {{ $item_assigned->executor()->middle_name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-margin-top">
                                    <div class="uk-grid">
                                        <div class="uk-width-2-6">
                                            <p class="uk-text-bold">Срок исполнения:</p>
                                        </div>
                                        <div class="uk-width-4-6">
                                            <p>{{ $item_assigned->execution_period }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-margin-top">
                                    <div class="uk-grid">
                                        <div class="uk-width-2-6">
                                            <p class="uk-text-bold">Статус:</p>
                                        </div>
                                        <div class="uk-width-4-6">
                                            @if($item_assigned->status == 2)
                                                <div class="status danger"></div>
                                            @elseif($item_assigned->status == 1)
                                                <div class="status success"></div>
                                            @elseif($item_assigned->status == 0)
                                                <div class="status warning"></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div class="uk-grid">
                                        <div class="uk-width-2-6">
                                            <p class="uk-text-bold">Основание:</p>
                                        </div>
                                        <div class="uk-width-4-6">
                                            <p>
                                                @if($item_assigned->correspondence_id)
                                                    <a href="{{ route('page.correspondence.show', ['correspondence' => $item->correspondence_id]) }}" target="_blank">Просмотреть</a>
                                                @else
                                                    Не имеется
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-margin-top">
                                    <div class="uk-grid">
                                        <div class="uk-width-2-6">
                                            <p class="uk-text-bold">Информация задания</p>
                                        </div>
                                        <div class="uk-width-4-6">
                                            <p>{{ $item_assigned->info }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection