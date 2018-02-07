<div class="wow-filter-form">
    <label for="#quests-compare-characters-select">Compare</label>
    <select id="quests-compare-characters-select">
        <option value="0">Select Character...</option>
        @foreach($otherCharacters as $char)
            @if(isset($compare) && ($compare==$char->id))
                <option value="{{ $char->id }}" selected="selected">{{ $char->name }} ({{ $char->countQuestsCompletedInCategory($category) }})</option>
            @else
                <option value="{{ $char->id }}">{{ $char->name }} ({{ $char->countQuestsCompletedInCategory($category) }})</option>
            @endif
        @endforeach
    </select>
    <button type="submit" class="btn btn-search" id="wow-button-submit-quests-compare">Compare</button>
</div>
<br>