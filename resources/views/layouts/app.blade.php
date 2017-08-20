<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('bower_components/uikit/css/uikit.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/uikit/css/components/form-advanced.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/uikit/css/components/form-file.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/uikit/css/components/form-password.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/uikit/css/components/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/uikit/css/components/accordion.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="app-wrapper uk-flex uk-flex-column">
        <header class="uk-width-1-1">
            <div class="uk-navbar uk-navbar--dark">
                <div class="uk-container uk-container-center">
                    <a href="{{ route('page.home') }}" class="uk-navbar-brand">
                        <span class="uk-height-1-1 uk-flex uk-flex-middle">
                            <img src="{{ asset('images/logo.png') }}" alt="" class="uk-margin-small-right">
                            <span>CSE</span>
                        </span>
                    </a>
                    <div class="uk-navbar-flip">
                        <form action="{{ route('logout.action') }}" class="uk-display-inline" method="post">
                            {{ csrf_field() }}
                            <button class="uk-button-link" type="submit">Выход</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        @yield('page')

        <footer class="uk-width-1-1">
            <div class="uk-container uk-container-center">
                <p class="uk-margin-small-top uk-margin-small-bottom uk-flex uk-flex-middle">
                    <i class="uk-icon-copyright uk-margin-small-right"></i>
                    <span class="uk-text-small">Все права защищены CSE 2017</span>
                </p>
            </div>
        </footer>
    </div>

    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('bower_components/uikit/js/uikit.min.js') }}"></script>
    <script src="{{ asset('bower_components/uikit/js/components/autocomplete.min.js') }}"></script>
    <script src="{{ asset('bower_components/uikit/js/components/datepicker.min.js') }}"></script>
    <script src="{{ asset('bower_components/uikit/js/components/accordion.min.js') }}"></script>
    <script src="{{ asset('bower_components/uikit/js/components/timepicker.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>