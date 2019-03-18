<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <canvas id="guild-tabard" width="50" height="50"></canvas>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav nav-tabs">
                <li nav-title="Characters" id="navbar-characters"><a href="/characters">Characters</a></li>
                <li nav-title="Dungeons" id="navbar-dungeons"><a href="/dungeons">Dungeons</a></li>
                @if ( CharacterDungeon::count() )
                    <li nav-title="Raids" id="navbar-raids"><a href="/raids">Raids</a></li>
                @endif
                <li nav-title="Professions" id="navbar-professions"><a href="/professions">Professions</a></li>
                @if ( Reputation::count() )
                    <li nav-title="Reputation" id="navbar-reputation"><a href="/reputation">Reputation</a></li>
                @endif
                @if ( CharacterQuest::count() )
                    <li nav-title="Quests" id="navbar-quests"><a href="/quests">Quests</a></li>
                @endif
                @if ( Auction::count() )
                    <li nav-title="Auctions" id="navbar-auctions"><a href="/auctions">Auctions</a></li>
                @endif
                <li nav-title="Stats" id="navbar-stats"><a href="/stats">Stats</a></li>
            </ul>
        </div>
    </div>
</nav>
