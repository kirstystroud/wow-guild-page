<table class="table">
    <thead>
        <th>Character</th>
        <th>Quests Completed</th>
    </thead>
    <tbody>
        @foreach($characters as $character)
            <tr>
                <td>@include('partials.character-link', [ 'character' => $character->character ])</td>
                <td category-id="{{ $category_id }}" character-id="{{ $character->character_id }}" class="td-category">
                    <a href="/">
                        <strong>{{ $character->count }}</strong>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
