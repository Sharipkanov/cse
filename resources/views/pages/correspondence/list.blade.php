@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    @include('includes.navigation')
    <div class="uk-margin-top uk-width-1-1">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-space-between">
                <div>
                    @if($type == 'income')
                        <a href="{{ route('page.correspondence.income.create') }}" class="uk-button uk-button-success">Создать регистрационную карточку входящего документа</a>
                    @endif
                </div>
                <div>
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
                            <th>Регистрационный номер</th>
                            <th>Дата регистрации</th>
                            <th>Корреспондент</th>
                            <th>ФИО исполнителя</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td></td>
                                <td>{{ $item->correspondence->name }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->executor }}</td>
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