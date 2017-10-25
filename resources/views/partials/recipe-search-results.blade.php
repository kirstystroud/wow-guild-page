<br>
<table class="table">
    <thead>
        <th>Recipe</th>
        <th>Profession</th>
        <th>Known by</th>
    </thead>
    @foreach($recipes as $recipe)
        <tr>
            <td class="td-normal">{{ $recipe->name }}</td>
            <td>{{ $recipe->profession->name }}</td>
            <td>
                @foreach($recipe->character_recipes as $char)
                    <a class="char-link char-{{ $char->character->character_class->id_ext }}" href="{{ $char->character->getLinkAddr() }}" target="_blank">{{ $char->character->name }}</a> 
                @endforeach
            </td>
        </tr>
    @endforeach
</table>