@include('partials.quests.compare-form')
<div class="quest-list">
    <table class="table table-hover">
        <thead>
            <th>Quest</th>
            <th>
                @include('partials.character-link', [ 'omitLevel' => true ])
            </th>
            <th>
                @include('partials.character-link', [ 'character' => $compareCharacter, 'omitLevel' => true ])
            </th>
        </thead>
        <tbody>
            @foreach($quests as $questName => $questData)
                <tr>
                    <td>{{ $questName }}</td>
                    @if($questData['character'])
                        <td class="td-yes">Yes</td>
                    @else
                        <td class="td-no">No</td>
                    @endif
                    @if($questData['compare'])
                        <td class="td-yes">Yes</td>
                    @else
                        <td class="td-no">No</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>