<br>
<h4>Dungeons Entered</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Dungeons Entered</th>
    </thead>
    <tbody>
        @foreach($data as $char)
            <tr>
                <td>
                    @include('partials.character-link', [ 'character' => $char , 'title' => true ])
                </td>
                <td>{{ $char->dungeons_entered }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
