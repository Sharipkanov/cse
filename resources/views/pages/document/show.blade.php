@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-space-between">
                <div>
                    @if($item->author()->id == auth()->user()->id && $item->status == 0)
                        <button data-uk-toggle="{target:'#approve', animation:'uk-animation-slide-right, uk-animation-slide-right'}" class="uk-button uk-button-primary" data-uk-modal>Согласовать</button>
                    @endif
                    @if($item->status == 2 && auth()->user()->id == $item->author()->id && !$item->correspondence() && !$item->task_id)
                        <button data-uk-toggle="{target:'#task', animation:'uk-animation-slide-right, uk-animation-slide-right'}" class="uk-button uk-button-primary" data-uk-modal>Прикрепить карточку задания</button>
                    @endif
                    @if(auth()->user()->id == $item->author()->id && $item->status > 2)
                        <form action="{{ route('page.document.copy', ['document' => $item->id]) }}" method="post" class="uk-display-inline">
                            {{ csrf_field() }}
                            <button  type="submit" class="uk-button">Создать копию</button>
                        </form>
                    @endif
                    @if($item->status == 2 && auth()->user()->id == $item->author()->id && !$item->correspondence())
                        <a href="{{ route('page.correspondence.outcome.create', ['document' => $item->id]) }}" class="uk-button uk-button-success">Создать исходящий документ</a>
                    @endif
                    @if($item->correspondence())
                        <a href="{{ route('page.correspondence.show', ['correspondence' => $item->correspondence()->id]) }}" class="uk-button uk-button-success">Просмотреть исходящую карточку</a>
                    @endif
                </div>
                <div>
                    <a href="{{ route('page.document.list') }}" class="uk-button uk-button-primary">К списку документов</a>
                </div>
            </div>

            @if($item->status == 0)
                <form id="approve" action="{{ route('page.document.approve.add', ['document' => $item->id]) }}" class="uk-form uk-margin-top uk-hidden" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="approve_add" value="1">
                    @foreach(array_reverse($leaders) as $key => $leader)
                        <div class="uk-form-row">
                            <label class="uk-flex uk-flex-middle">
                                <span class="uk-margin-small-right"><input type="checkbox" name="approvers[]" value="{{ $leader->id }}"></span>
                                <span>{{ $leader->last_name }} {{ $leader->first_name }} {{ $leader->middle_name }}</span>
                            </label>
                        </div>
                    @endforeach
                    <hr>
                    <div class="uk-form-row uk-text-right">
                        <button class="uk-button uk-button-success">Отправить на согласование</button>
                    </div>
                </form>
            @endif

            @if(!$item->task_id)
                <form id="task" action="{{ route('page.document.task.set', ['document' => $item->id]) }}" class="uk-form uk-margin-top uk-hidden" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="task_id" value="">
                    <div class="uk-margin-top uk-position-relative">
                        <label class="uk-form-label">Карточка задания:</label>
                        <div class="uk-form-controls uk-margin-small-top">
                            <input type="text" name="" id="task-search-input" placeholder="Введите регистрационный номер карточки задания" class="uk-width-1-1{{ ($errors->has('task_id')) ? ' uk-form-danger' : '' }}">
                            <input type="hidden" name="task_id"  value="" id="task-input">
                        </div>
                        <div class="drop-down" id="task-drop-down">

                        </div>
                    </div>
                    <hr>
                    <div class="uk-form-row uk-text-right">
                        <button class="uk-button uk-button-success">Исполнить карточку задания</button>
                    </div>
                </form>
            @endif

            @if($approve)
                <form action="{{ route('page.document.approve.answer', ['document' => $item->id, 'approve' => $approve->id]) }}" class="uk-form uk-margin-top" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="approve_answer" value="1">
                    <div class="uk-form-row">
                        <label class="uk-flex uk-flex-middle approve-type">
                            <span class="uk-margin-small-right"><input type="radio" name="status" value="1"></span>
                            <span>Согласен</span>
                        </label>
                        <label class="uk-flex uk-flex-middle uk-margin-small-top approve-type">
                            <span class="uk-margin-small-right"><input type="radio" name="status" value="2"></span>
                            <span>Согласен с замечаниями:</span>
                        </label>
                        <label class="uk-flex uk-flex-middle uk-margin-small-top approve-type">
                            <span class="uk-margin-small-right"><input type="radio" name="status" value="3"></span>
                            <span>Не согласен</span>
                        </label>
                    </div>
                    <div class="uk-form-row uk-hidden" id="approve-info">
                        <div class="uk-form-controls uk-margin-small-top">
                            <textarea name="info" class="uk-width-1-1" rows="6" placeholder="Примичание"></textarea>
                        </div>
                    </div>
                    <div class="uk-form-row uk-text-right">
                        <button class="uk-button uk-button-success">Отправить</button>
                    </div>
                </form>
            @endif

            <form class="uk-form uk-margin-top uk-margin-large-bottom" action="{{ route('page.document.update', ['document' => $item->id]) }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <span class="uk-flex uk-flex-space-between uk-flex-middle uk-h3">
                    <span>{{ $title }}</span>
                    <span class="uk-h5">Дата создания {{ $item->created_at }}</span>
                </span>
                <hr>

                <div class="uk-margin-top">
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
                            <p class="uk-text-bold">{{ ($item->author()->subdivision_id) ? 'Подразделение автора' : 'Отдел автора' }}</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($item->author()->subdivision_id) ? $item->author()->subdivision()->name : $item->author()->department()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Тема документа</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Дополнительная информация</p>
                        </div>
                        <div class="uk-width-4-6">
                            @if($item->status == 4)
                                <div class="uk-form-controls uk-margin-small-top">
                                    <textarea name="info" class="uk-width-1-1{{ $errors->has('info') ? ' uk-form-danger' : '' }}" rows="9">{{ old('info') }}</textarea>
                                </div>
                                @if ($errors->has('info'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('info') }}</p>
                                @endif
                            @else
                                <p>{{ $item->info }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Приложения</p>
                        </div>
                        <div class="uk-width-4-6">
                            @if($item->status == 4)
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="file" name="files[]" multiple>
                                </div>
                                @if ($errors->has('file'))
                                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('file') }}</p>
                                @endif
                            @else
                                @if(count($item->fileList))
                                    @foreach($item->fileList as $file)
                                        <a href="{{ route('page.file.download', ['file' => $file->id]) }}" target="_blank">{{ $file->name }}</a>
                                    @endforeach
                                @else
                                    <p>Нет вложенных файлов</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Основание</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>
                                @if(!$item->task_id)
                                    Нет основания
                                @else
                                    <a class="" href="{{ route('page.task.show', ['task' => $item->task()->id]) }}">Просмотреть</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @if($item->status && $item->status == 4)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6"></div>
                            <div class="uk-width-4-6 uk-text-right">
                                <button type="submit" class="uk-button uk-button-success">Сохранить</button>
                            </div>
                        </div>
                    </div>
                @elseif($item->status != 0)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Статус</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p class="fw-flex fw-flex-middle">
                                    @if($item->status == 3)
                                        <span class="status danger uk-margin-small-right"></span>
                                        <span>Не прошел согласование</span>
                                    @elseif($item->status == 2)
                                        <span class="status success uk-margin-small-right"></span>
                                        <span>Согласован</span>
                                    @elseif($item->status == 1)
                                        <span class="status warning uk-margin-small-right"></span>
                                        <span>На согласовании</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->parent_id && $item->status != 4)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Копия документа</p>
                            </div>
                            <div class="uk-width-4-6">
                                <a href="{{ route('page.document.show', ['document' => $item->copy()->id]) }}" target="_blank">{{ $item->copy()->type()->name .': № '. $item->copy()->register_number }}</a>
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($approves))
                    <hr>
                    @foreach($approves as $approve)
                        <div class="{{ (!$loop->first) ? 'uk-margin-top' : '' }}">
                            <div class="uk-grid">
                                <div class="uk-width-2-6">
                                    <p class="uk-text-bold">{{ $approve->approver()->last_name .' '. str_limit($approve->approver()->first_name, 1, '.') . str_limit($approve->approver()->middle_name, 1, '') }}</p>
                                </div>
                                <div class="uk-width-4-6">
                                    @if($approve->status == 0)
                                        <p>Ожидает</p>
                                    @elseif($approve->status == 1)
                                        <p>Согласован</p>
                                    @elseif($approve->status == 2)
                                        <p>Согласен с замечаниями</p>
                                        <textarea class="uk-width-1-1" rows="7" disabled>{{ $approve->info }}</textarea>
                                    @elseif($approve->status == 3)
                                        <p>Не согласен</p>
                                        <textarea class="uk-width-1-1" rows="7" disabled>{{ $approve->info }}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </form>
        </div>
    </div>
@endsection