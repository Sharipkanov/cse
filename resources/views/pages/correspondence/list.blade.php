@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    @include('includes.navigation')
    <div class="uk-margin-top uk-width-1-1">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="uk-flex uk-flex-middle">
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status uk-margin-small-right"></div><small>Зарегистрирован (Не присвоен)</small></div>
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status warning uk-margin-small-right"></div><small>На регистраций</small></div>
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status success uk-margin-small-right"></div><small>Зарегистрирован</small></div>
                </div>
                <div>
                    @if(auth()->user()->position_id == 4)
                        @if($type == 'income')
                            <a href="{{ route('page.correspondence.income.create') }}" class="uk-button uk-button-success">Создать регистрационную карточку входящего документа</a>
                        @endif
                        <button class="uk-button uk-button-primary" data-uk-toggle="{target:'#register-number', animation:'uk-animation-slide-right, uk-animation-slide-right'}">Зарезервировать номер</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->position_id == 4)
        <div class="uk-container uk-container-center uk-hidden" id="register-number">
            <form action="{{ route('page.number.register') }}" class="uk-margin-top uk-form" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="is_income" value="{{ ($type == 'income') ? 1 : 0 }}">
                <div>
                    <label>Количество номеров</label>
                    <div class="uk-margin-small-top">
                        <input type="text" name="count" value="{{ old('count') }}" class="uk-width-1-1">
                    </div>
                </div>
                @if ($errors->has('count'))
                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('count') }}</p>
                @endif
                <div class="uk-margin-top uk-text-right">
                    <button class="uk-button uk-button-success">Зарезервировать</button>
                </div>
            </form>
        </div>
    @endif

    <div class="uk-width-1-1 uk-margin-top uk-margin-bottom">
        <div class="uk-container uk-container-center">

            @if(count($items))
                <table class="uk-table uk-table-condensed">
                    <thead>
                        <tr>
                            <th class="width-content">Регистрационный номер</th>
                            <th>ФИО исполнителя</th>
                            <th class="width-content">Корреспондент</th>
                            <th class="width-content">Дата регистрации</th>
                            <th class="width-content"></th>
                            <th class="width-content"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td class="width-content">{{ $item->register_number }}</td>
                                <td>{{ $item->executor_fullname }}</td>
                                <td class="width-content">{{ $item->correspondent()->name }}</td>
                                <td class="width-content">{{ $item->created_at }}</td>
                                <td class="width-content uk-text-center">
                                    @if($item->status == 3)
                                        <span class="status success uk-margin-small-right"></span>
                                    @elseif($item->status == 2)
                                        <span class="status warning uk-margin-small-right"></span>
                                    @elseif($item->status == 1)
                                        <span class="status uk-margin-small-right"></span>
                                    @else
                                        <span class="status default"></span>
                                    @endif
                                </td>
                                <td class="width-content"><a href="{{ route('page.correspondence.show', ['correspondence' => $item->id]) }}" class="uk-button">Просмотреть</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="uk-margin-large-top">
                    {{ $items->links() }}
                </div>
            @else
                <div class="bg-white boxed">
                    <span class="uk-text-small uk-text-uppercase">Список {{ ($type == 'income') ? 'входящих' : 'исходящих'}} документов корреспонденции пуст</span>
                </div>
            @endif
        </div>
    </div>
@endsection