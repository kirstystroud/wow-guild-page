<br>
<h4>Top 10 Most Kills per death</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Kills per Death</th>
    </thead>
    </tbody>
        @foreach($characters as $char)
            <tr class="members-tr-{{ $char->character_class->id_ext }}">
                <td><a href="{{ $char->getLinkAddr() }}" target="_blank">{{ $char->name }}</a> <span>({{ $char->level }})</span></td>
                <td>{{ $char->kdr }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
