@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    @include('includes.navigation')
    <div class="uk-margin-top uk-width-1-1">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="uk-margin-top uk-flex uk-flex-middle">
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status uk-margin-small-right"></div><small>На регистраций</small></div>
                    <div class="uk-flex uk-flex-middle uk-margin-right"><div class="status success uk-margin-small-right"></div><small>Зарегистрирован</small></div>
                </div>
                <div>
                    @if($type == 'income')
                        <a href="{{ route('page.correspondence.income.create') }}" class="uk-button uk-button-success">Создать регистрационную карточку входящего документа</a>
                    @endif
                    <a href="#" class="uk-button uk-button-primary">Зарезервировать регистрационный номер</a>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-width-1-1 uk-margin-top">
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
                                <td class="width-content"></td>
                                <td>{{ $item->executor_fullname }}</td>
                                <td class="width-content">{{ $item->correspondent()->name }}</td>
                                <td class="width-content">{{ $item->created_at }}</td>
                                <td class="width-content uk-text-center">
                                    @if($item->status)
                                        <div class="status success"></div>
                                    @else
                                        <div class="status"></div>
                                    @endif
                                </td>
                                <td class="width-content"><a href="{{ route('page.correspondence', ['correspondence' => $item->id]) }}" class="uk-button">Просмотреть</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="uk-margin-large-top">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection