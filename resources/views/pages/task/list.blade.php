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
                    @if(auth()->user()->position_id == 2)
                        <button class="uk-button uk-button-primary" data-uk-toggle="{target:'#task-toggle',  animation:'uk-animation-slide-right, uk-animation-slide-right'}">Создать карточку задания</button>
                    @endif
                </div>
            </div>
            @if(auth()->user()->position_id == 2)
                <div id="task-toggle" class="uk-margin-top {{ ($errors->any()) ? '' : ' uk-hidden' }}">
                    <form action="{{ route('page.task.store') }}" class="uk-form" method="post">
                        {{ csrf_field() }}
                        @if($departments)
                            <div class="uk-form-row">
                                <label class="uk-form-label">Укажите срок исполнения</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <select name="executor_id" class="uk-width-1-1">
                                        <option value="">Выберите исполнителя</option>
                                        @foreach($departments as $department)
                                            @if($department->leader_id)
                                                <option value="{{ $department->leader()->id }}" {{ (old('executor_id') == $department->leader()->id) ? 'selected' : ''}}>{{ $department->leader()->last_name }} {{ $department->leader()->first_name }} {{ $department->leader()->middle_name }}</option>
                                            @endif
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
                                <input type="text" value="{{ old('execution_date') }}" class="uk-width-1-1" name="execution_date" placeholder="Выберите cрок исполнения" data-uk-datepicker="{minDate: '{{ date('Y-m-d') }}', format:'YYYY-MM-DD', i18n: {months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], weekdays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']}}">
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
                            <button type="submit" class="uk-button uk-button-success">Создать</button>
                        </div>
                    </form>
                </div>
            @endif
            @if(count($items))
                <form class="uk-form uk-margin-top" method="get">
                    {{ csrf_field() }}
                    <div class="uk-flex">
                        <div class="uk-flex-item-auto uk-margin-small-right">
                            <input type="text" name="register_number" placeholder="Регистрационный номер" class="uk-width-1-1">
                        </div>
                        <div class="uk-flex-item-auto uk-margin-small-right">
                            <input type="text" class="uk-width-1-1" placeholder="Дата регистрации" name="date" data-uk-datepicker="{format:'YYYY-MM-DD', i18n: {months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], weekdays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']}}">
                        </div>
                        <div class="uk-flex-item-auto uk-margin-small-right">
                            <input type="text" name="author" placeholder="Автор" class="uk-width-1-1">
                        </div>
                        <div class="uk-flex-item-auto uk-margin-small-right">
                            <input type="text" name="name" placeholder="Тема документа" class="uk-width-1-1">
                        </div>
                        <div class="uk-flex-item-none"><button class="uk-button uk-button-danger">Фильтровать</button></div>
                    </div>
                </form>

                <table class="uk-table uk-table-condensed">
                    <thead>
                    <tr>
                        <th class="width-content">Регистрационный номер</th>
                        <th>Автор</th>
                        <th>Исполнитель</th>
                        <th class="width-content">Срок выполнения</th>
                        <th class="width-content">Дата регистрации</th>
                        <th class="width-content">Статус</th>
                        <th class="width-content"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td class="width-content">{{ $item->register_number }}</td>
                            <td>{{ $item->author()->last_name .' '. str_limit($item->author()->first_name, 1, '.') . str_limit($item->author()->middle_name, 1, '') }}</td>
                            <td>{{ $item->executor()->last_name .' '. str_limit($item->executor()->first_name, 1, '.') . str_limit($item->executor()->middle_name, 1, '') }}</td>
                            <td class="width-content">{{ $item->execution_period }}</td>
                            <td class="width-content">{{ $item->created_at }}</td>
                            <td class="width-content uk-text-center">
                                @if($item->status == 2)
                                    <div class="status danger"></div>
                                @elseif($item->status == 1)
                                    <div class="status success"></div>
                                @elseif($item->status == 0)
                                    <div class="status warning"></div>
                                @endif
                            </td>
                            <td class="width-content">
                                <a href="{{ route('page.task.show', ['task' => $item->id]) }}" class="uk-button uk-button-small">Просмотреть</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="uk-margin-top uk-margin-large-bottom">
                    {{ $items->links() }}
                </div>
            @else
                <div class="bg-white boxed uk-margin-top">
                    <span class="uk-text-small uk-text-uppercase">Список карточек задания пуст</span>
                </div>
            @endif
        </div>
    </div>
@endsection