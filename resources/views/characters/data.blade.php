<table class="table table-hover">
    <thead>
        @include('partials.th-sort-heading', [ 'label' => 'Name', 'key' => 'name' ])
        @include('partials.th-sort-heading', [ 'label' => 'Level', 'key' => 'level' ])
        @include('partials.th-sort-heading', [ 'label' => 'Class', 'key' => 'class' ])
        @include('partials.th-sort-heading', [ 'label' => 'Race', 'key' => 'race' ])
        @include('partials.th-sort-heading', [ 'label' => 'Spec', 'key' => 'spec' ])
        @include('partials.th-sort-heading', [ 'label' => 'iLvl', 'key' => 'ilvl' ])
        @include('partials.th-sort-heading', [ 'label' => 'Last Activity', 'key' => 'last_activity' ])
    </thead>
    <tbody>
        @foreach($characters as $character)
            <tr>
                <td>@include('partials.character-link', [ 'character' => $character , 'omitLevel' => true ])</td>
                <td>{{ $character->level }}</td>
                <td>
                    <span>
                        <a href="#" data-toggle="tooltip" data-placement="right" data-original-title="{{ $character->character_class->name }}">
                            <img src="{{ $character->getClassImg() }}">
                        </a>
                    </span>
                </td>
                <td>
                    <span>
                        <a href="#" data-toggle="tooltip" data-placement="right" data-original-title="{{ $character->race->name }}">
                            <img src="{{ $character->getRaceImg() }}">
                        </a>
                    </span>
                </td>
                <td>
                    @if($character->spec_id)
                        <span>
                            <a href="#" data-toggle="tooltip" data-placement="right" data-original-title="{{ $character->spec->name }}">
                                <img class="fmts-img" src="{{ $character->spec->getIconLocation() }}">
                            </a>
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $character->ilvl }}</td>
                <td>{{ isset($character->last_activity) ? $character->last_activity : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
