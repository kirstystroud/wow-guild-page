<div class="quest-list">
    @foreach($quests as $quest)
        <p>{{ $quest->name }}</p>
    @endforeach
</div>