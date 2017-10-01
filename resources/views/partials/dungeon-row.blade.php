<div class="panel-heading">
    <h4>
        <a data-toggle="collapse" href="#dungeon-row-{{ $dungeon->id }}">
            {{ $dungeon->getHeading() }}
        </a>
        <p class="text-right pull-right">{{ $dungeon->location }}</p>
    </h4>
</div>
<div id="dungeon-row-{{ $dungeon->id }}" class="panel-body panel-collapse collapse">
    <table class="table">
        <thead>
            <th>Name</th>
            <th>DPS</th>
            <th>Tank</th>
            <th>Healer</th>
        </thead>
        <tbody>
            @foreach($dungeon->getAvailableChars() as $char)
                <tr class="members-tr-{{ $char->character_class->id_ext }} char-{{ $char->id }}">
                    <td><a href="{{ $char->getLinkAddr() }}" target="_blank">{{ $char->name }}</a> <span>({{ $char->level }})</span></td>
                    <td class="td-yes">Yes</td>
                    @if($char->canTank())
                        <td class="td-yes">Yes</td>
                    @else
                        <td class="td-no">No</td>
                    @endif
                    @if($char->canHeal())
                        <td class="td-yes">Yes</td>
                    @else
                        <td class="td-no">No</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
