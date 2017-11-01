<div class="panel-heading">
    <h4>
        <a data-toggle="collapse" href="#reputation-row-{{ $reputation->id }}">
            {{ $reputation->name }}
        </a>
    </h4>
</div>
<div id="reputation-row-{{ $reputation->id }}" class="panel-body panel-collapse collapse">
    <table class="table">
        <thead>
            <th>Name</th>
            <th>Standing</th>
            <th>Progress</th>
        </thead>
        <tbody>
            @foreach($reputation->getCharacters() as $char)
                <tr class="members-tr-{{ $char->character->character_class->id_ext }} char-{{ $char->character->id }}">
                    <td><a href="{{ $char->character->getLinkAddr() }}" target="_blank">{{ $char->character->name }} <span>({{ $char->character->level }})</span></a></td>
                    <td class="standing-{{ $char->standing }}">{{ Reputation::getStandings()[$char->standing] }}</td>
                    <td>{{ $char->getProgress() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
