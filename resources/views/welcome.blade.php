<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>WoW Guild Page</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- WoW Guild Tabard -->
        <script type="text/javascript" src="http://eu.battle.net/wow/static/local-common/js/common-game-site.js?v=58-126"></script>
        <script type="text/javascript" src="http://eu.battle.net/wow/static/js/character/guild-tabard.js?v=126"></script>

        <!-- Application CSS / JS -->
        <link href="css/styles.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="js/script.js"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div class="container container-fmts">
            @include('partials.navbar')
            @yield('content')
        </div>
    </body>
</html>
