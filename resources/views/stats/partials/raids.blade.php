<br>
<h4>Raids Entered</h4>

<table class="table">
    <thead>
        <th>Character</th>
        <th>Raids Entered</th>
    </thead>
    <tbody>
        @foreach($data as $char)
            <tr>
                <td>
                    @include('partials.character-link', [ 'character' => $char , 'title' => true ])
                </td>
                <td>{{ $char->raids_entered }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
