<table class="table">
    <thead>
        <th>Category</th>
        <th>Completed Quests</th>
    </thead>
    <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</strong></td>
                <td category-id="{{ $category->category_id }}" character-id="{{ $character_id }}" class="td-category">
                    <a href="/">
                        <strong>{{ $category->count }}</strong>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
