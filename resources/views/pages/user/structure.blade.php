@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="uk-width-1-1 uk-margin-top">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div>
                    <a href="{{ route('page.department.create') }}" class="uk-button uk-button-primary">Создать отдел</a>
                    <a href="{{ route('page.subdivision.create') }}" class="uk-button uk-button-primary">Создать подотдел</a>
                </div>
                <div>
                    <a href="{{ route('page.user.create') }}" class="uk-button uk-button-primary">Добавить сотрудника</a>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-width-1-1 uk-margin-top uk-margin-large-bottom">
        <div class="uk-container uk-container-center">
            <div class="uk-form">
                <h3>{{ $name }}</h3>
                <h4 class="uk-margin-top">Директор: {{ $director->last_name .' '. $director->first_name .' '. $director->middle_name }}</h4>
                <ul class="uk-tab" data-uk-tab="{connect:'#departments-switcher', animation: 'slide-right'}">
                    @foreach($departments as $department)
                        <li><a href="">{{ $department->name }}</a></li>
                    @endforeach
                </ul>

                <ul id="departments-switcher" class="uk-switcher uk-margin">
                    @foreach($departments as $department)
                        <li>
                            @if($department->leader_id)
                                <?php $departmentLeader = $department->leader(); ?>
                                @if($departmentLeader)
                                    <h4>Начальник отдела: {{ $departmentLeader->last_name .' '. str_limit($departmentLeader->first_name, 1, '.') . str_limit($departmentLeader->middle_name, 1, '') }} ({{ $departmentLeader->position()->name }})</h4>
                                @endif
                                <hr>
                                <?php $departmentUsers = $department->department_users(true); ?>
                            @else
                                <?php $departmentUsers = $department->department_users(); ?>
                            @endif
                            @if(count($departmentUsers))
                                <table class="uk-table uk-table-condensed">
                                    <thead>
                                    <tr>
                                        <th>ФИО сотрудника</th>
                                        <th class="width-content">Должность</th>
                                        <th class="width-content"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($departmentUsers as $departmentUser)
                                        <tr>
                                            <td>{{ $departmentUser->last_name .' '. str_limit($departmentUser->first_name, 1, '.') . str_limit($departmentUser->middle_name, 1, '') }}</td>
                                            <td class="width-content">{{ $departmentUser->position()->name }}</td>
                                            <td class="width-content"><a href="#" class="uk-button uk-button-success uk-button-small">Просмотреть</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            @if($department->has_subdivision())
                                <div>
                                    <?php $departmentSubdivision = $department->subdivisions(); ?>
                                    @if(count($departmentSubdivision))
                                        <div class="uk-grid uk-margin-top">
                                            <div class="uk-width-2-6">
                                                <ul class="uk-tab uk-tab-left" data-uk-switcher="{connect:'#subdivision-{{ $department->id }}', animation: 'slide-right'}">
                                                    @foreach($departmentSubdivision as $subdivision)
                                                        <li><a href="">{{ $subdivision->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="uk-width-4-6">
                                                <ul class="uk-switcher" id="subdivision-{{ $department->id }}">
                                                    @foreach($departmentSubdivision as $subdivision)
                                                        <li>
                                                            <?php $subdivisionLeader = $subdivision->leader(); ?>
                                                            @if($subdivisionLeader)
                                                                <h5>Начальник подотдела: {{ $subdivisionLeader->last_name .' '. str_limit($subdivisionLeader->first_name, 1, '.') . str_limit($subdivisionLeader->middle_name, 1, '') }} ({{ $subdivisionLeader->position()->name }})</h5>
                                                                <hr>
                                                            @endif
                                                            <?php $subdivionUsers = $subdivision->subdivision_users(); ?>
                                                            @if($subdivionUsers)
                                                                <?php $subdivionUsers = $subdivision->subdivision_users(); ?>
                                                                @if(count($subdivionUsers))
                                                                    <table class="uk-table uk-table-condensed">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>ФИО сотрудника</th>
                                                                            <th class="width-content">Должность</th>
                                                                            <th class="width-content"></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($subdivionUsers as $departmentUser)
                                                                            <tr>
                                                                                <td>{{ $departmentUser->last_name .' '. str_limit($departmentUser->first_name, 1, '.') . str_limit($departmentUser->middle_name, 1, '') }}</td>
                                                                                <td class="width-content">{{ $departmentUser->position()->name }}</td>
                                                                                <td class="width-content"><a href="#" class="uk-button uk-button-success uk-button-small">Просмотреть</a></td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @endif
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection