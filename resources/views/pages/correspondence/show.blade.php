@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-space-between">
                <div>
                    @if(auth()->user()->position_id == 4 && $item->status == 0)
                        <form action="{{ route('page.correspondence.edit', ['correspondence' => $item->id]) }}" class="uk-display-inline" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="register" value="1">
                            <button type="submit" class="uk-button uk-button-success">Зарегистрировать</button>
                        </form>
                    @endif
                </div>
                <div>
                    <a href="{{ route('page.expertise.list') }}" class="uk-button uk-button-primary">К списку экспертиз</a>
                </div>
            </div>

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

                @if($item->reply_correspondence_id)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Ответ на исходящий:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->reply_correspondence()->register_number}} <a href="{{ route('page.correspondence.show', ['correspondence' => $item->reply_correspondence()->id]) }}">Просмотреть</a></p>
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

                @if(!$item->is_income)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Основание:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p><a class="uk-button uk-button-success" href="{{ route('page.document.show', ['document' => $item->document()->id]) }}">Просмотреть</a></p>
                            </div>
                        </div>
                    </div>
                @else
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
                @endif

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Статус</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p class="fw-flex fw-flex-middle">
                                @if($item->status)
                                    <span class="status success uk-margin-small-right"></span>
                                    <span>Зарегистрирован</span>
                                @else
                                    <span class="status warning uk-margin-small-right"></span>
                                    <span>На регистраций</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection