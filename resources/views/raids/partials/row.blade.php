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
                <tr class="char-{{ $row->character->id }}">
                    <td>@include('partials.character-link', [ 'character' => $row->character ])</td>
                    <td>{{ $row->lfr ? $row->lfr : ' ' }}</td>
                    <td>{{ $row->normal ? $row->normal : ' ' }}</td>
                    <td>{{ $row->heroic ? $row->heroic : ' ' }}</td>
                    <td>{{ $row->mythic ? $row->mythic : ' ' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No data available</p>
    @endif
</div>
