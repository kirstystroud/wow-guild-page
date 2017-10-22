<br>
<h4>Character Kills</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Kills</th>
    </thead>
    </tbody>
        @foreach($data['kills'] as $char)
            <tr class="members-tr-{{ $char->character_class->id_ext }}">
                <td><a href="{{ $char->getLinkAddr() }}" target="_blank">{{ $char->getTitle() }}</a> <span>({{ $char->level }})</span></td>
                <td>{{ $char->kills }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<h4>Character Kills per Death</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Kills per Death</th>
    </thead>
    </tbody>
        @foreach($data['kdr'] as $char)
            <tr class="members-tr-{{ $char->character_class->id_ext }}">
                <td><a href="{{ $char->getLinkAddr() }}" target="_blank">{{ $char->getTitle() }}</a> <span>({{ $char->level }})</span></td>
                <td>{{ $char->kdr }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
