@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="uk-margin-top uk-flex uk-flex-middle">
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status uk-margin-small-right"></div><small>Новый (Еще не отправлен на согласование)</small></div>
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status warning uk-margin-small-right"></div><small>На соглосавиний</small></div>
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status success uk-margin-small-right"></div><small>Согласован</small></div>
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status danger uk-margin-small-right"></div><small>Не прошел соглосования</small></div>
                </div>
                <div><a href="{{ route('page.document.create') }}" class="uk-button uk-button-primary =">Создать документ</a></div>
            </div>

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
                        <th>Тема документа</th>
                        <th class="width-content">Автор</th>
                        <th class="width-content">Дата регистрации</th>
                        <th class="width-content">Статус</th>
                        <th class="width-content"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td class="width-content">{{ $item->register_number }}</td>
                            <td>{{ str_limit($item->name, 20) }}</td>
                            <td class="width-content">{{ $item->author()->last_name .' '. str_limit($item->author()->first_name, 1, '.') . str_limit($item->author()->middle_name, 1, '') }}</td>
                            <td class="width-content">{{ $item->created_at }}</td>
                            <td class="width-content uk-text-center">
                                @if($item->status == 3)
                                    <div class="status danger"></div>
                                @elseif($item->status == 2)
                                    <div class="status success"></div>
                                @elseif($item->status == 1)
                                    <div class="status warning"></div>
                                @elseif($item->status == 0)
                                    <div class="status"></div>
                                @endif
                            </td>
                            <td class="width-content">
                                <a href="{{ route('page.document.show', ['document' => $item->id]) }}" class="uk-button uk-button-small">Просмотреть</a>
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
                    <span class="uk-text-small uk-text-uppercase">Список документов пуст</span>
                </div>
            @endif
        </div>
    </div>
@endsection