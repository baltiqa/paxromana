<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="/web/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/web/assets/libs/css/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/libs/css/style.css">
    <title>Tenebris</title>
</head>
<body @if(request()->segment(2) == 'staffchat') class="chat-body" @endif>
<div class="dashboard-main-wrapper">
    @include('panel::layouts.header')

    @include('panel::layouts.sidebar')

    <div class="dashboard-wrapper">
        @yield('content')

    @include('panel::layouts.footer')
    </div>
</div>
</body>
</html>
