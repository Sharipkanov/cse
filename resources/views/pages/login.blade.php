@extends('layouts.empty')

@section('title'){{ $title }}@endsection

@section('page')
    <div class="b-login">
        <form class="uk-form" action="{{ route('login.action') }}" method="post">
            {{ csrf_field() }}
            <fieldset>
                <div class="uk-form-row">
                    <input type="text" placeholder="Почта" class="uk-width-1-1{{ $errors->has('email') ? ' uk-form-danger' : '' }}" value="{{ old('email') }}" name="email">
                </div>
                @if ($errors->has('email'))
                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('email') }}</p>
                @endif
                <div class="uk-form-row">
                    <input type="password" placeholder="Пароль" class="uk-width-1-1{{ $errors->has('password') ? ' uk-form-danger' : '' }}" name="password">
                </div>
                @if ($errors->has('password'))
                    <p class="uk-text-small uk-text-danger uk-margin-small">{{ $errors->first('password') }}</p>
                @endif
                {{--<div class="uk-form-row">
                    <div class="uk-flex uk-flex-space-between uk-flex-middle">
                        <div class="uk-flex uk-flex-middle">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>Запомнить меня</span>
                            </label>
                        </div>
                        <div><a href="#">Забыли пароль</a></div>
                    </div>
                </div>--}}
                <div class="uk-form-row">
                    <button type="submit" class="uk-button uk-button-primary uk-width-1-1">Войти в систему</button>
                </div>
            </fieldset>
        </form>
    </div>
@endsection