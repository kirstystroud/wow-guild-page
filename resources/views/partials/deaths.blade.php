<br>
<h4>Top 10 Most Deaths</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Deaths</th>
    </thead>
    </tbody>
        @foreach($characters as $char)
            <tr class="members-tr-{{ $char->character_class->id_ext }}">
                <td>{{ $char->name }} <span>({{ $char->level }})</span></td>
                <td>{{ $char->deaths }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
