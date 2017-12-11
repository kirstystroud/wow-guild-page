<table class="table">
    <thead>
        <th>Category</th>
        <th>Completed Quests</th>
    </thead>
    <tbody>
        @foreach($categories as $category)
            <tr>
                <td category-id="{{ $category->category_id }}" class="td-category"><a href="/"><strong>{{ $category->name }}</strong></a></td>
                <td>{{ $category->count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
