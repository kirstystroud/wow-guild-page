<table class="table">
    <thead>
        <th id="th-name" class="members-th">
            <a href="#" class="table-sort" sort="name">
                Name
                @if(isset($sorting['name']))
                    @include('partials.sort-icon', ['sort' => $sorting['name']])
                @endif
            </a>
        </th>
        <th id="th-level" class="members-th">
            <a href="#" class="table-sort" sort="level">
                Level
                @if(isset($sorting['level']))
                    @include('partials.sort-icon', ['sort' => $sorting['level']])
                @endif
            </a>
        </th>
        <th id="th-class" class="members-th">
            <a href="#" class="table-sort" sort="class">
                Class
                @if(isset($sorting['class']))
                    @include('partials.sort-icon', ['sort' => $sorting['class']])
                @endif
            </a>
        </th>
        <th id="th-race" class="members-th">
            <a href="#" class="table-sort" sort="race">
                Race
                @if(isset($sorting['race']))
                    @include('partials.sort-icon', ['sort' => $sorting['race']])
                @endif
            </a>
        </th>
        <th id="th-spec" class="members-th">
            <a href="#" class="table-sort" sort="spec">
                Spec
                @if(isset($sorting['spec']))
                    @include('partials.sort-icon', ['sort' => $sorting['spec']])
                @endif
            </a>
        </th>
        <th id="th-ilvl" class="members-th">
            <a href="#" class="table-sort" sort="ilvl">
                iLvl
                @if(isset($sorting['ilvl']))
                    @include('partials.sort-icon', ['sort' => $sorting['ilvl']])
                @endif
            </a>
        </th>
    </thead>
    <tbody>
        @foreach($characters as $character)
            <tr class="members-tr members-tr-{{ $character->class_id }}">
                <td>{{ $character->name }}</td>
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
            </tr>
        @endforeach
    </tbody>
</table>