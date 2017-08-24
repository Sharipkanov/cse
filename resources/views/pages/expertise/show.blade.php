@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-space-between">
                <div>
                    @if(count($executors))
                        @if($income_task && $income_task->status == 0 || auth()->user()->position_id == 2 && $item->status == 0)
                            <button data-uk-toggle="{target:'#approve', animation:'uk-animation-slide-right, uk-animation-slide-right'}" class="uk-button uk-button-primary" data-uk-modal>Отправить на поручение</button>
                        @endif
                    @endif
                    @if($income_task && $income_task->status == 0 && !$outcome_task)
                        <form action="{{ route('page.expertise.task.set') }}" class="uk-display-inline" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="expertise_task_id" value="{{ $income_task->id }}">
                            <button type="submit" class="uk-button uk-button-success">Принять</button>
                        </form>
                    @endif
                </div>
                <div>
                    <a href="{{ route('page.expertise.list') }}" class="uk-button uk-button-primary">К списку экспертиз</a>
                </div>
            </div>

            @if(count($executors))
                @if($income_task && $income_task->status == 0 || auth()->user()->position_id == 2 && $item->status == 0)
                    <form id="approve" action="{{ route('page.expertise.task.store') }}" class="uk-form uk-margin-top uk-hidden" method="post">
                    {{ csrf_field() }}
                    @if($task_parent)
                        <input type="hidden" name="parent_id" value="{{ $task_parent }}">
                    @endif
                    <input type="hidden" name="expertise_id" value="{{ $item->id }}">
                    @foreach($executors as $executor)
                        <div class="uk-form-row">
                            <label class="uk-flex uk-flex-middle">
                                <span class="uk-margin-small-right"><input type="checkbox" name="execute[{{ $executor['leader']->id }}][executor]" value="{{ $executor['leader']->id }}" class="leader-checkobx"></span>
                                <span>{{ $executor['leader']->last_name }} {{ $executor['leader']->first_name }} {{ $executor['leader']->middle_name }}</span>
                            </label>
                            @foreach($executor['specialities'] as $speciality)
                                <div class="uk-margin-left uk-margin-small-top">
                                    <label class="uk-flex uk-flex-middle">
                                        <span class="uk-margin-small-right"><input type="checkbox" name="execute[{{ $executor['leader']->id }}][specialities][]" value="{{ $speciality->id }}" class="speciality-checkbox" data-speciality="{{ $speciality->id }}"></span>
                                        <span>{{ $speciality->code .' - '. $speciality->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <hr>
                    <div class="uk-form-row uk-text-right">
                        <button class="uk-button uk-button-success">Поручить</button>
                    </div>
                </form>
                @endif
            @endif

            @if(count($tasks))
                <table class="uk-table uk-table-condensed">
                    <thead>
                    <tr>
                        <th class="width-content">Код</th>
                        <th>Название</th>
                        <th>Передано от</th>
                        <th class="width-content">Передано к</th>
                        <th class="width-content">Статус</th>
                        <th class="width-content"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        @foreach($task->specialities as $speciality)
                            <tr>
                                <td class="width-content">{{ $speciality->code }}</td>
                                <td>{{ $speciality->name }}</td>
                                <td class="width-content">{{ $task->author()->last_name .' '. str_limit($task->author()->first_name, 1, '.') . str_limit($task->author()->middle_name, 1, '') }}</td>
                                <td class="width-content">{{ $task->executor()->last_name .' '. str_limit($task->executor()->first_name, 1, '.') . str_limit($task->executor()->middle_name, 1, '') }}</td>
                                <td class="width-content">
                                    @if($task->status == 4)
                                        Отклонен в соглсовании
                                    @elseif($task->status == 3)
                                        Согласован
                                    @elseif($task->status == 2)
                                        Ждет соглосования
                                    @elseif($task->status == 1)
                                        В процесе
                                    @elseif($task->status == 0)
                                        Отправлен на поручение
                                    @endif
                                </td>
                                <td class="width-content">
                                    @if($task->status)
                                        @if(auth()->user()->id == $task->executor()->id)
                                            @if($task->status == 3)
                                                <a class="uk-button uk-width-1-1" href="{{ route('page.expertise.info.show', ['expertiseInfo' => $expertiseInfos[$speciality->id]->id]) }}">Просмотреть</a>
                                            @else
                                                <a class="uk-button uk-width-1-1" href="{{ route('page.expertise.edit', ['expertiseInfo' => $expertiseInfos[$speciality->id]->id]) }}">Редактировать</a>
                                            @endif
                                        @else
                                            <a class="uk-button uk-width-1-1" href="{{ route('page.expertise.info.show', ['expertiseInfo' => $expertiseInfos[$speciality->id]->id]) }}">Просмотреть</a>
                                        @endif
                                    @else
                                        <button class="uk-button" disabled="">Переприсвоен</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
            </table>
            @endif

            <div class="uk-form uk-margin-top uk-margin-large-bottom">
                <span class="uk-flex uk-flex-space-between uk-flex-middle uk-h3">
                    <span>{{ $title }}</span>
                    <span class="uk-h5">Дата создания: {{ $item->created_at }}</span>
                </span>
                <hr>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Делопроизводитель</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->author()->last_name }} {{ $item->author()->first_name }} {{ $item->author()->middle_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Фабула</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->info }}</p>
                        </div>
                    </div>
                </div>

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

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Категория дела:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->category()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">№ дела:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->case_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">№ статьи:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->article_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Статус:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->status()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Дополнительный статус:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->addition_status()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Шифр экспертизы:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <ul class="uk-list">
                                @foreach($item->specialities as $speciality)
                                    <li><span>{{ $speciality->code .' - '. $speciality->name }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Регион назначивший экспертизу:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->region()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Наименование органа:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->agency()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Орган назначивший экспертизу:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->organ()->name }}</p>
                        </div>
                    </div>
                </div>

                @if($item->expertise_organ_name)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Введите название органа:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->expertise_organ_name }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <hr>

                <div class="">
                    <span class="uk-h4">Данные лица назначевшего экспертизу</span>
                </div>

                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">ФИО:</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ $item->expertise_user_fullname }}</p>
                        </div>
                    </div>
                </div>


                @if($item->expertise_user_position)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Должность:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->expertise_user_position }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->expertise_user_rank)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Звание:</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->expertise_user_rank }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->status != 0)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Статус</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p class="fw-flex fw-flex-middle">
                                    @if($item->status == 2)
                                        <span class="status success uk-margin-small-right"></span>
                                        <span>Завершен</span>
                                    @elseif($item->status == 1)
                                        <span class="status warning uk-margin-small-right"></span>
                                        <span>В процесе</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection