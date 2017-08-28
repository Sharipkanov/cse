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
                    @if(!$expertiseInfo->is_stopped && $expertiseInfo->status < 3)
                        <form action="{{ route('page.expertise.approve', ['expertiseInfo' => $expertiseInfo->id]) }}" class="uk-display-inline" method="post">
                            {{ csrf_field() }}
                            <button class="uk-button uk-button-primary">Согласовать</button>
                        </form>
                        @if(!$expertiseOutOfSuspension)
                            <button class="uk-button uk-button-danger" data-uk-toggle="{target:'#suspension', animation:'uk-animation-slide-right, uk-animation-slide-right'}">Приостановить</button>
                        @endif
                    @endif
                    @if($expertiseInfo->is_stopped && !count($approves))
                        <form action="{{ route('page.expertise.restart', ['expertiseInfo' => $expertiseInfo->id]) }}" class="uk-display-inline" method="post">
                            {{ csrf_field() }}
                            <button type="submit" class="uk-button uk-button-success">Возобновть</button>
                        </form>
                    @endif
                </div>
                <div>
                    <a href="{{ route('page.expertise.list') }}" class="uk-button uk-button-primary">К списку экспертиз</a>
                </div>
            </div>

            @if(!$expertiseOutOfSuspension && !$expertiseInfo->is_stopped)
                <div id="suspension" class="uk-form uk-margin-top uk-hidden">
                    <div class="uk-form-row">
                        <label class="uk-form-label">Причина приостановления</label>
                        <div class="uk-form-controls uk-margin-small-top">
                            <select class="uk-width-1-1" name="reason_for_suspension" id="reason_for_suspension">
                                <option value="" selected disabled>Выберите причину приостановления</option>
                                <option value="Ходатайство">Ходатайство</option>
                                <option value="Командировка">Командировка</option>
                                <option value="Больничный лист">Больничный лист</option>
                                <option value="Вызов в суд">Вызов в суд</option>
                                <option value="Участие в комплексной экспертизе">Участие в комплексной экспертизе</option>
                            </select>
                        </div>

                        <form id="form-sc" action="{{ route('page.expertise.stop', ['expertiseInfo' => $expertiseInfo->id]) }}" class="uk-margin-top uk-hidden" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="reason_for_suspension" class="reason_for_suspension">
                            <div class="uk-position-relative">
                                <label class="uk-form-label">Выберите основание</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" name="" id="sc-search-input" placeholder="Введите номер входящего документа" class="uk-width-1-1">
                                    <input type="hidden" name="correspondence_id"  value="" id="sc-input">
                                </div>
                                <div class="drop-down" id="sc-drop-down">

                                </div>
                            </div>
                            <div class="uk-margin-top uk-text-right">
                                <button class="uk-button uk-button-success">Приостановить</button>
                            </div>
                        </form>

                        <form id="form-ds" action="{{ route('page.expertise.stop', ['expertiseInfo' => $expertiseInfo->id]) }}" class="uk-margin-top uk-hidden" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="reason_for_suspension" class="reason_for_suspension">
                            <div class="uk-position-relative">
                                <label class="uk-form-label">Выберите основание</label>
                                <div class="uk-form-controls uk-margin-small-top">
                                    <input type="text" name="" id="ds-search-input" placeholder="Введите номер документа" class="uk-width-1-1">
                                    <input type="hidden" name="document_id"  value="" id="ds-input">
                                </div>
                                <div class="drop-down" id="ds-drop-down">

                                </div>
                            </div>
                            <div class="uk-margin-top uk-text-right">
                                <button class="uk-button uk-button-success">Приостановить</button>
                            </div>
                        </form>

                        <div>
                            
                        </div>
                    </div>
                </div>
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
                                        <p>Согласован с замечаниями</p>
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
                    <span class="uk-h5">Дата создания {{ $item->created_at }}</span>
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
                                <p class="uk-text-bold">ФИО</p>
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

            <form action="{{ route('page.expertise.update', ['expertiseInfo' => $expertiseInfo->id]) }}" class="uk-form uk-margin-bottom" method="post">
                {{csrf_field()}}
                <div class="uk-grid uk-grid-width-1-2">
                    <div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Категория сложности</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <select class="uk-width-1-1" name="category_of_difficulty">
                                    <option value="" {{ (!$expertiseInfo->category_of_difficulty) ? 'selected' : '' }} disabled>Выберите категорию сложности</option>
                                    <option value="Простая" {{ ($expertiseInfo->category_of_difficulty == 'Простая') ? 'selected' : '' }}>Простая</option>
                                    <option value="Средней степени сложности" {{ ($expertiseInfo->category_of_difficulty == 'Средней степени сложности') ? 'selected' : '' }}>Средней степени сложности</option>
                                    <option value="Сложная" {{ ($expertiseInfo->category_of_difficulty == 'Сложная') ? 'selected' : '' }}>Сложная</option>
                                    <option value="Особо сложная" {{ ($expertiseInfo->category_of_difficulty == 'Особо сложная') ? 'selected' : '' }}>Особо сложная</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Количество поставленных вопросов</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите количество поставленных вопросов" class="uk-width-1-1{{ $errors->has('questions_count') ? ' uk-form-danger' : '' }}" name="questions_count" value="{{ ($expertiseInfo->questions_count) ? $expertiseInfo->questions_count : '' }}">
                            </div>
                            @if ($errors->has('questions_count'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('questions_count') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Количество объектов </label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите количество объектов" class="uk-width-1-1{{ $errors->has('objects_count') ? ' uk-form-danger' : '' }}" name="objects_count" value="{{ ($expertiseInfo->objects_count) ? $expertiseInfo->objects_count : '' }}">
                            </div>
                            @if ($errors->has('objects_count'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('objects_count') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Срок производства</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input type="text" value="{{ ($expertiseInfo->expiration_date) ? $expertiseInfo->expiration_date : '' }}" class="uk-width-1-1{{ $errors->has('expiration_date') ? ' uk-form-danger' : '' }}" name="expiration_date" placeholder="Укажите cрок производства" data-uk-datepicker="{minDate: '{{ $expertiseInfoCreatedDate }}', maxDate: '{{ date('Y-m-d', strtotime($expertiseInfoCreatedDate.' + 30 days')) }}', format:'YYYY-MM-DD', i18n: {months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], weekdays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']}}">
                            </div>
                            @if ($errors->has('expiration_date'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('expiration_date') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Результата исследования</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <select class="uk-width-1-1" name="result_of_research">
                                    <option value="" {{ (!$expertiseInfo->result_of_research) ? 'selected' : '' }} disabled>Выберите результата исследования</option>
                                    <option value="Заключение" {{ ($expertiseInfo->result_of_research == 'Заключение') ? 'selected' : '' }}>Заключение</option>
                                    <option value="СНДЗ" {{ ($expertiseInfo->result_of_research == 'СНДЗ') ? 'selected' : '' }}>СНДЗ</option>
                                    <option value="Возврат без исполнения" {{ ($expertiseInfo->result_of_research == 'Возврат без исполнения') ? 'selected' : '' }}>Возврат без исполнения</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Категорические выводы</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите категорические выводы" class="uk-width-1-1{{ $errors->has('categorical_conclusions') ? ' uk-form-danger' : '' }}" name="categorical_conclusions" value="{{ ($expertiseInfo->categorical_conclusions) ? $expertiseInfo->categorical_conclusions : '' }}">
                            </div>
                            @if ($errors->has('categorical_conclusions'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('categorical_conclusions') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Вероятные выводы</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите вероятные выводы" class="uk-width-1-1{{ $errors->has('probable_conclusions') ? ' uk-form-danger' : '' }}" name="probable_conclusions" value="{{ ($expertiseInfo->probable_conclusions) ? $expertiseInfo->probable_conclusions : '' }}">
                            </div>
                            @if ($errors->has('probable_conclusions'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('probable_conclusions') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">НПВ</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите НПВ" class="uk-width-1-1{{ $errors->has('wnp') ? ' uk-form-danger' : '' }}" name="wnp" value="{{ ($expertiseInfo->wnp) ? $expertiseInfo->wnp : '' }}">
                            </div>
                            @if ($errors->has('wnp'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('wnp') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Количество не решенных вопросов</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите количество не решенных вопросов " class="uk-width-1-1{{ $errors->has('unsolved_issues_count') ? ' uk-form-danger' : '' }}" name="unsolved_issues_count" value="{{ ($expertiseInfo->unsolved_issues_count) ? $expertiseInfo->unsolved_issues_count : '' }}">
                            </div>
                            @if ($errors->has('unsolved_issues_count'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('unsolved_issues_count') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Дано выводов (ВСЕГО)</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите количество" class="uk-width-1-1{{ $errors->has('conclusions_count') ? ' uk-form-danger' : '' }}" name="conclusions_count" value="{{ ($expertiseInfo->conclusions_count) ? $expertiseInfo->conclusions_count : '' }}">
                            </div>
                            @if ($errors->has('conclusions_count'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('conclusions_count') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Категорические выводы (ПОЛОЖИТЕЛЬНЫЕ)</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите количество" class="uk-width-1-1{{ $errors->has('categorical_conclusions_positive') ? ' uk-form-danger' : '' }}" name="categorical_conclusions_positive" value="{{ ($expertiseInfo->categorical_conclusions_positive) ? $expertiseInfo->categorical_conclusions_positive : '' }}">
                            </div>
                            @if ($errors->has('categorical_conclusions_positive'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('categorical_conclusions_positive') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Категорические выводы (ОТРИЦАТЕЛЬНЫЕ)</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите количество" class="uk-width-1-1{{ $errors->has('categorical_conclusions_negative') ? ' uk-form-danger' : '' }}" name="categorical_conclusions_negative" value="{{ ($expertiseInfo->categorical_conclusions_negative) ? $expertiseInfo->categorical_conclusions_negative : '' }}">
                            </div>
                            @if ($errors->has('categorical_conclusions_negative'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('categorical_conclusions_negative') }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Стоимость исследования</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input data-number type="text" placeholder="Укажите стоимость исследования " class="uk-width-1-1{{ $errors->has('cost') ? ' uk-form-danger' : '' }}" name="cost" value="{{ ($expertiseInfo->cost) ? $expertiseInfo->cost : '' }}">
                            </div>
                            @if ($errors->has('cost'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('cost') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Отметка об оплате</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <select class="uk-width-1-1" name="payment_note">
                                    <option value="" disabled {{ (!$expertiseInfo->payment_note) ? 'selected' : '' }}>Выберите отметку об оплате</option>
                                    <option value="Платежное поручение" {{ ($expertiseInfo->payment_note == 'Платежное поручение') ? 'selected' : '' }}>Платежное поручение</option>
                                    <option value="Приходный кассовый ордер" {{ ($expertiseInfo->payment_note == 'Приходный кассовый ордер') ? 'selected' : '' }}>Приходный кассовый ордер</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">№ документа</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input type="text" placeholder="Укажите номер документа" class="uk-width-1-1{{ $errors->has('payment_note_document_number') ? ' uk-form-danger' : '' }}" name="payment_note_document_number" value="{{ ($expertiseInfo->payment_note_document_number) ? $expertiseInfo->payment_note_document_number : '' }}">
                            </div>
                            @if ($errors->has('payment_note_document_number'))
                                <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('payment_note_document_number') }}</p>
                            @endif
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Дата документа</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <input value="{{ ($expertiseInfo->payment_note_document_date) ? $expertiseInfo->payment_note_document_date : '' }}" type="text" class="uk-width-1-1{{ $errors->has('payment_note_document_date') ? ' uk-form-danger' : '' }}" name="payment_note_document_date" placeholder="Выберите дату документа" data-uk-datepicker="{minDate: '{{ date('Y-m-d') }}', format:'YYYY-MM-DD', i18n: {months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], weekdays: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']}}">
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Причины возврата без исполнения</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <textarea name="return_reason" class="uk-width-1-1{{ $errors->has('return_reason') ? ' uk-form-danger' : '' }}" rows="7" placeholder="Укажите причину возврата без исполнения">{{ $expertiseInfo->return_reason }}</textarea>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Причины СНДЗ</label>
                            <div class="uk-form-controls uk-margin-small-top">
                                <textarea name="rigs" class="uk-width-1-1{{ $errors->has('rigs') ? ' uk-form-danger' : '' }}" rows="7" placeholder="Укажите причины СНДЗ">{{ $expertiseInfo->rigs }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @if($expertiseInfo->status == 0 && !$expertiseInfo->is_stopped)
                <hr>
                <div class="uk-text-right">
                    <button type="submit" class="uk-button uk-button-success">Сохранить</button>
                </div>
                @endif
            </form>
            <div class="uk-form uk-margin-large-bottom">
                <div>
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