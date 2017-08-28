@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <?php
    $expertiseInfoCreatedDate = explode(' ', $expertiseInfo->created_at)[0];
    $expertiseUntilSuspensionDate = date('Y-m-d', strtotime($expertiseInfoCreatedDate.' + 5 days'));
    $expertiseOutOfSuspension = strtotime($expertiseUntilSuspensionDate) <= time();
    ?>
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-space-between">
                <div>
                </div>
                <div>
                    <a href="{{ route('page.expertise.list') }}" class="uk-button uk-button-primary">К списку экспертиз</a>
                </div>
            </div>
            @if($approve && $previousApproved)
                <form action="{{ route('page.expertise.approve.answer', ['expertiseInfo' => $expertiseInfo->id, 'expertiseApprove' => $approve->id]) }}" class="uk-form uk-margin-top" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="approve_answer" value="1">
                    <div class="uk-form-row">
                        <label class="uk-flex uk-flex-middle approve-type">
                            <span class="uk-margin-small-right"><input type="radio" name="status" value="1"></span>
                            <span>Согласен</span>
                        </label>
                        <label class="uk-flex uk-flex-middle uk-margin-small-top approve-type">
                            <span class="uk-margin-small-right"><input type="radio" name="status" value="2"></span>
                            <span>Соглосен с замечаниями</span>
                        </label>
                        <label class="uk-flex uk-flex-middle uk-margin-small-top approve-type">
                            <span class="uk-margin-small-right"><input type="radio" name="status" value="3"></span>
                            <span>Не согласен</span>
                        </label>
                    </div>
                    <div class="uk-form-row uk-hidden" id="approve-info">
                        <div class="uk-form-controls uk-margin-small-top">
                            <textarea name="info" class="uk-width-1-1" rows="6" placeholder="Замечания"></textarea>
                        </div>
                    </div>
                    <div class="uk-form-row uk-text-right">
                        <button class="uk-button uk-button-success">Отправить</button>
                    </div>
                </form>
            @endif

            @if(count($approves))
                <div class="uk-margin-top uk-form">
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
                                        <p>Согласован с замечаними</p>
                                        <textarea class="uk-width-1-1" rows="7" disabled>{{ $approve->info }}</textarea>
                                    @elseif($approve->status == 3)
                                        <p>Отклонен</p>
                                        <textarea class="uk-width-1-1" rows="7" disabled>{{ $approve->info }}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="uk-accordion uk-margin-top uk-margin-bottom" data-uk-accordion="{showfirst: false}">
                <span class="uk-flex uk-flex-space-between uk-flex-middle uk-h3 uk-accordion-title">
                    <span>{{ $title }}</span>
                    <span class="uk-h5">Дата создания: {{ $item->created_at }}</span>
                </span>
                <div class="uk-accordion-content uk-form">
                    <div>
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
                                <p class="uk-text-bold">Категория дела</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->category()->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">№ дела</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->case_number }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">№ статьи</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->article_number }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Статус</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->status()->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Дополнительный статус</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->addition_status()->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Шифр экспертизы</p>
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
                                <p class="uk-text-bold">Регион назначивший экспертизу</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->region()->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Наименование органа</p>
                            </div>
                            <div class="uk-width-4-6">
                                <p>{{ $item->agency()->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Орган назначивший экспертизу</p>
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
                                    <p class="uk-text-bold">Введите название органа</p>
                                </div>
                                <div class="uk-width-4-6">
                                    <p>{{ $item->expertise_organ_name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="">
                        <span class="uk-h4">Данные лица назначившего экспертизу</span>
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
                                    <p class="uk-text-bold">Должность</p>
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
                                    <p class="uk-text-bold">Звание</p>
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

            <div class="uk-form uk-margin-bottom">
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Категория сложности</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->category_of_difficulty) ? $expertiseInfo->category_of_difficulty : '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Количество поставленных вопросов</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->questions_count) ? $expertiseInfo->questions_count : '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Количество объектов</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->objects_count) ? $expertiseInfo->objects_count : '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Срок производства</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->expiration_date) ? $expertiseInfo->expiration_date : '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Результата исследования</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->result_of_research) ? $expertiseInfo->result_of_research : '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Категорические выводы</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->categorical_conclusions) ? $expertiseInfo->categorical_conclusions : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Вероятные выводы</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->probable_conclusions) ? $expertiseInfo->categorical_conclusions : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">НПВ</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->wnp) ? $expertiseInfo->wnp : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Количество не решенных вопросов</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->unsolved_issues_count) ? $expertiseInfo->unsolved_issues_count : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Дано выводов (ВСЕГО)</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->conclusions_count) ? $expertiseInfo->conclusions_count : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Категорические выводы (ПОЛОЖИТЕЛЬНЫЕ)</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->categorical_conclusions_positive) ? $expertiseInfo->categorical_conclusions_positive : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Категорические выводы (ОТРИЦАТЕЛЬНЫЕ)</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->categorical_conclusions_negative) ? $expertiseInfo->categorical_conclusions_negative : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Стоимость исследования</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->cost) ? $expertiseInfo->cost : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Отметка об оплате</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->payment_note) ? $expertiseInfo->payment_note : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">№ документа</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->payment_note_document_number) ? $expertiseInfo->payment_note_document_number : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Дата документа</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->payment_note_document_date) ? $expertiseInfo->payment_note_document_date : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Причины возврата без исполнения</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->return_reason) ? $expertiseInfo->return_reason : '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Причины СНДЗ</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->rigs) ? $expertiseInfo->rigs : '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-form uk-margin-large-bottom">
                <div class="uk-form-row">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Дата приостановления</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->suspension_date) ? $expertiseInfo->suspension_date : 'Еще не был приостановлен' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Причина приостановления</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->reason_for_suspension) ? $expertiseInfo->reason_for_suspension : 'Еще не был приостановлен' }}</p>
                        </div>
                    </div>
                </div>

                @if($expertiseInfo->document_id || $expertiseInfo->correspondence_id)
                    <div class="uk-margin-top">
                        <div class="uk-grid">
                            <div class="uk-width-2-6">
                                <p class="uk-text-bold">Основание</p>
                            </div>
                            <div class="uk-width-4-6">
                                @if($expertiseInfo->document_id)
                                    <a target="_blank" href="{{ route('page.document.show', ['document' => $expertiseInfo->document_id]) }}">Просмотреть</a>
                                @endif
                                @if($expertiseInfo->correspondence_id)
                                    <a target="_blank" href="{{ route('page.correspondence.show', ['correspondence' => $expertiseInfo->correspondence_id]) }}">Просмотреть</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Дата возобновления </p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->renewal_date) ? $expertiseInfo->renewal_date : 'Еще не был приостановлен' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Дата завершения исследования </p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->finish_date) ? $expertiseInfo->finish_date : 'Будет указано после завершения экспертизы' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Фактическое количество дней нахождения материалов в территориальном подразделении</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->actual_days) ? $expertiseInfo->actual_days : 'Экспертиза еще не была завершена' }}</p>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-top">
                    <div class="uk-grid">
                        <div class="uk-width-2-6">
                            <p class="uk-text-bold">Количество дней в производстве</p>
                        </div>
                        <div class="uk-width-4-6">
                            <p>{{ ($expertiseInfo->production_days) ? $expertiseInfo->production_days : 'Экспертиза еще не была завершена' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection