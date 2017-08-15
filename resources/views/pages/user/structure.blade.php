@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div>
                    <a href="#department-create" class="uk-button uk-button-primary" data-uk-modal>Создать отдел</a>
                    <a href="#subdivision-create" class="uk-button uk-button-primary" data-uk-modal>Создать подотдел</a>
                </div>
                <div>
                    <a href="{{ route('page.user.create') }}" class="uk-button uk-button-primary">Добавить сотрудника</a>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-form">

            </div>
        </div>
    </div>
    <div id="department-create" class="uk-modal">
        <div class="uk-modal-dialog">
            <a class="uk-modal-close uk-close"></a>
            <div>
                Создать отдел
            </div>
        </div>
    </div>

    <div id="subdivision-create" class="uk-modal">
        <div class="uk-modal-dialog">
            <a class="uk-modal-close uk-close"></a>
            <div>
                Создать подотдел
            </div>
        </div>
    </div>
@endsection