<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>WoW Guild Page</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="css/styles.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="js/script.js"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div class="container-fmts">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-chars">Characters</a></li>
                <li><a data-toggle="tab" href="#tab-available-dungeons">Available Dungeons</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-chars" class="tab-pane fade in active">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4>Characters</h4>
                        </div>
                        <div class="panel-body" id="guild-members-list">
                            Loading ...
                        </div>
                    </div>
                </div>
                <div id="tab-available-dungeons" class="tab-pane fade">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4>
                                Available Dungeons
                                <p class="pull-right text-right">
                                    <select id="dungeon-char-select">
                                        <option value="0">Select Character ...</option>
                                        @foreach($characters as $char)
                                            <option value="{{ $char->id }}">{{ $char->name }}</option>
                                        @endforeach
                                    </select>
                                </p>
                            </h4>
                        </div>
                        <div class="panel-body" id="guild-dungeons-available-list">
                            Loading ...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
