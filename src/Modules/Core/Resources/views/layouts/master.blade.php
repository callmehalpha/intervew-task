<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="icon" href="{{asset('images/logo.png')}}" type="image/png">
    @stack('meta')
    <title>@yield('pageName') | {{config('app.name')}}</title>
    @stack('styles')
</head>
<body class="{!!  $bodyClass ?? ''!!}">

@yield('app')

@stack('modals')
@stack('scripts')
</body>
</html>
