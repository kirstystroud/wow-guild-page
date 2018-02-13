<br>
<h4>PVP Kills</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Kills</th>
    </thead>
    <tbody>
        @foreach($data as $char)
            <tr>
                <td>
                    @include('partials.character-link', [ 'character' => $char , 'title' => true ])
                </td>
                <td>{{ number_format($char->pvp_kills) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
