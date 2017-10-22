<br>
<h4>Character Deaths</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Deaths</th>
    </thead>
    </tbody>
        @foreach($data['mostDeaths'] as $char)
            <tr class="members-tr-{{ $char->character_class->id_ext }}">
                <td><a href="{{ $char->getLinkAddr() }}" target="_blank">{{ $char->getTitle() }}</a> <span>({{ $char->level }})</span></td>
                <td>{{ $char->deaths }}</td>
            </tr>
        @endforeach
        <tr>
            <td>....</td>
            <td>....</td>
        </tr>
        @foreach($data['leastDeaths'] as $char)
            <tr class="members-tr-{{ $char->character_class->id_ext }}">
                <td><a href="{{ $char->getLinkAddr() }}" target="_blank">{{ $char->getTitle() }}</a> <span>({{ $char->level }})</span></td>
                <td>{{ $char->deaths }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
