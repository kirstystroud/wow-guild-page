<table class="table table-hover">
    <thead>
        <th>Character</th>
        <th>Quests Completed</th>
    </thead>
    <tbody>
        @foreach($characters as $char)
            @if($char->character['name'])
                <tr class="members-tr">
                    <td>@include('partials.character-link', [ 'character' => $char->character ])</td>
                    <td character-id="{{ $char->character['id'] }}" category-id="0" class="td-category">
                        <a href="/">
                            <strong>{{ number_format($char->count) }}</strong>
                        </a>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
