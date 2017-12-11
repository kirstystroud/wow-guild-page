<table class="table">
    <thead>
        <th>Character</th>
        <th>Quests Completed</th>
    </thead>
    <tbody>
        @foreach($characters as $char)
            @if($char->character['name'])
                <tr class="members-tr">
                    <td>@include('partials.character-link', [ 'character' => $char->character ])</td>
                    <td>{{ $char->count }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
