<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="format-detection" content="telephone=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>非公式いせぶいDB</title>
        <meta name="description" content="個人Vtuberグループいせぶい非公式データベース">

        <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/icons/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <link rel="mask-icon" href="/images/icons/safari-pinned-tab.svg" color="#ff9cbb">
        <meta name="apple-mobile-web-app-title" content="非公式いせぶいDB">
        <meta name="application-name" content="非公式いせぶいDB">
        <meta name="msapplication-TileColor" content="#00aba9">
        <meta name="theme-color" content="#ff9cbb">
        
        <!-- Fonts -->
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Raleway:wght@700&family=Reggae+One&family=Zen+Maru+Gothic:wght@700&display=swap');
        </style>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
