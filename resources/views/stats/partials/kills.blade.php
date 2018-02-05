<br>
<h4>Character Kills</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Kills</th>
    </thead>
    <tbody>
        @foreach($data['kills'] as $char)
            <tr>
                <td>
                    @include('partials.character-link', [ 'character' => $char , 'title' => true ])
                </td>
                <td>{{ number_format($char->kills) }}</td>
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
            <tr>
                <td>
                    @include('partials.character-link', [ 'character' => $char , 'title' => true ])
                </td>
                <td>{{ number_format($char->kdr) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
