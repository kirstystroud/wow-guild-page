<div class="panel-heading">
    <h4>
        <a data-toggle="collapse" href="#raid-row-{{ $raid->id }}">
            {{ $raid->getHeading() }}
        </a>
        <p class="text-right pull-right">{{ $raid->location }}</p>
    </h4>
</div>
<div id="raid-row-{{ $raid->id }}" class="panel-body panel-collapse collapse">
    @if(count($raid->getCharacterRaidData()))
        <table class="table">
            <thead>
                <th>Character</th>
                <th>LFR</th>
                <th>Normal</th>
                <th>Heroic</th>
                <th>Mythic</th>
            </thead>
            @foreach($raid->getCharacterRaidData() as $row)
                <tr class="members-tr members-tr-{{ $row->character->character_class->id_ext }}">
                    <td><a href="{{ $row->character->getLinkAddr() }}" target="_blank">{{ $row->character->name }}</a> <span>({{ $row->character->level }})</span></td>
                    <td>{{ $row->lfr }}</td>
                    <td>{{ $row->normal }}</td>
                    <td>{{ $row->heroic }}</td>
                    <td>{{ $row->mythic }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No data available</p>
    @endif
</div>
