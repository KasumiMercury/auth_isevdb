<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="format-detection" content="telephone=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
        <title>非公式{{ $currentMember -> display }}DB | SharePage</title>
        <meta name="description" content="個人Vtuberグループいせぶい非公式データベースいせぶい所属{{ $currentMember -> display }}DBに登録されたデータのシェアページ">
    
        <meta property=2og:title" content="非公式{{ $currentMember -> display }}DB | SharePage" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="https://isevdb.sakura.ne.jp/{{ $currentMember->name }}/share/{{ $id }}" />
        <meta property="og:image" content="https://i.ytimg.com/vi/{{ $player-> VideoID }}/hqdefault.jpg" />
        <meta property="og:site_name" content="非公式{{ $currentMember -> display }}DB">
        <meta property="og:description" content="個人Vtuberグループいせぶい非公式データベースいせぶい所属{{ $currentMember -> display }}DBに登録されたデータのシェアページ">
        <meta property="og:locale" content="ja_JP">
        <meta name="twitter:card" content="summary_large_image">

        <!-- Fonts -->
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Raleway:wght@700&family=Reggae+One&family=Zen+Maru+Gothic:wght@700&display=swap');
        </style>
    </head>
    <body class="font-sans antialiased">
        <div id="app">
            <p>testCompornent{{ $id }}</p>
            <share-page :id="{{ $id }}" :current-member="{{ $currentMember }}" :player="{{ $player }}"></share-page>
        </div>
        <script src="{{ mix('js/ogp.js') }}" defer></script>
    </body>
</html>
