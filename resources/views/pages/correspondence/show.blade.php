@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-space-between">
                <div>
                    <div class="uk-flex uk-flex-middle">
                        <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status default uk-margin-small-right"></div><small>Не присвоен</small></div>
                        <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status uk-margin-small-right"></div><small>На исполнении</small></div>
                        <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status warning uk-margin-small-right"></div><small>На регистраций</small></div>
                        <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status success uk-margin-small-right"></div><small>Зарегистрирован</small></div>
                    </div>
                </div>
                <div>
                    @if(auth()->user()->position_id == 4 && $item->status == 2 && !$item->is_income)
                        <form action="{{ route('page.correspondence.edit', ['correspondence' => $item->id]) }}" class="uk-display-inline" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="register" value="1">
                            <button type="submit" class="uk-button uk-button-success">Зарегистрировать</button>
                        </form>
                    @endif
                    @if(auth()->user()->position_id == 2 && !$item->status)
                        <button class="uk-button uk-button-primary" data-uk-toggle="{target:'#task-toggle',  animation:'uk-animation-slide-right, uk-animation-slide-right'}">Создать карточку задания</button>
                    @endif
                </div>
            </div>

            @if(auth()->user()->position_id == 2 && !$item->status)
                <div id="task-toggle" class="uk-margin-top {{ ($errors->any()) ? '' : ' uk-hidden' }}">
                    <form action="{{ route('page.task.correspondence.store') }}" class="uk-form" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="correspondence_id" value="{{ $item->id }}">
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

            @if($item->status == 3)
                <form id="approve" action="{{ route('page.document.approve.add', ['document' => $item->id]) }}" class="uk-form uk-margin-top uk-hidden" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="approve_add" value="1">
                    {{--@foreach(array_reverse($leaders) as $key => $leader)
                        <div class="uk-form-row">
                            <label class="uk-flex uk-flex-middle">
                                <span class="uk-margin-small-right"><input type="checkbox" name="approvers[]" value="{{ $leader->id }}:{{$key + 1}}"></span>
                                <span>{{ $leader->last_name }} {{ $leader->first_name }} {{ $leader->middle_name }}</span>
                            </label>
                        </div>
                    @endforeach--}}
                    <hr>
                    <div class="uk-form-row uk-text-right">
                        <button class="uk-button uk-button-success">Отправить на согласование</button>
                    </div>
                </form>
            @endif

            <div class="uk-form uk-margin-top uk-margin-large-bottom">
                <span class="uk-flex uk-flex-space-between uk-flex-middle uk-h3">
                    <span>{{ $title }}</span>
                    <span class="uk-h5">Дата создания: {{ $item->created_at }}</span>
                </span>

                <hr>

                @if($item->register_number)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Регистрационный номер:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->register_number }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Язык обращения:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->language()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Корреспондент:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->correspondent()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">ФИО исполнителя:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->executor_fullname }}</p>
                        </div>
                    </div>
                </div>

                @if($item->outcome_number)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Исходящий №:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->outcome_number }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->outcome_number)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Дата исходящего:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->outcome_date }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->execution_period)
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
                @endif

                @if($item->recipent_id)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Получатель:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->recipent()->last_name .' '. str_limit($item->recipent()->first_name, 1, '.') . str_limit($item->recipent()->middle_name, 1, '') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->document_type_id && $item->is_income)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Тип документа:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->document_type()->name}}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Страницы:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->pages }}</p>
                        </div>
                    </div>
                </div>

                @if($item->reply_correspondence_id)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Ответ на {{ ($item->is_income) ? 'исходящийй' : 'входящий'}}:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p><a href="{{ route('page.correspondence.show', ['correspondence' => $item->reply_correspondence()->id]) }}">Просмотреть</a></p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->is_income)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Приложения</p>
                            </div>
                            <div class="uk-width-4-6">
                                @if(count($item->fileList))
                                    @foreach($item->fileList as $file)
                                        <a href="{{ route('page.file.download', ['file' => $file->id]) }}" target="_blank">{{ $file->name }}</a>
                                    @endforeach
                                @else
                                    <p>Нет вложенных файлов</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Основание:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p><a href="{{ route('page.document.show', ['document' => $item->document()->id]) }}">Просмотреть</a></p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Статус</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p class="fw-flex fw-flex-middle">
                                @if($item->status == 3)
                                    <span class="status success uk-margin-small-right"></span>
                                @elseif($item->status == 2)
                                    <span class="status warning uk-margin-small-right"></span>
                                @elseif($item->status == 1)
                                    <span class="status uk-margin-small-right"></span>
                                @else
                                    <span class="status default"></span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection