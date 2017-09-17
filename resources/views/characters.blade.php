<table class="table">
    <thead>
        <th id="th-name" class="members-th">Name</th>
        <th id="th-level" class="members-th">Level</th>
        <th id="th-class" class="members-th">Class</th>
        <th id="th-race" class="members-th">Race</th>
        <th id="th-spec" class="members-th">Spec</th>
        <th id="th-ilvl" class="members-th">iLvl</th>
    </thead>
    <tbody>
        @foreach($characters as $character)
            <tr class="members-tr">
                <td>{{ $character->name }}</td>
                <td>{{ $character->level }}</td>
                <td>
                    <span>
                        <a href="#" data-toggle="tooltip" data-placement="right" data-original-title="{{ $character->getClassName() }}">
                            <img src="{{ $character->getClassImg() }}">
                        </a>
                    </span>
                </td>
                <td>
                    <span>
                        <a href="#" data-toggle="tooltip" data-placement="right" data-original-title="{{ $character->getRaceName() }}">
                            <img src="{{ $character->getRaceImg() }}">
                        </a>
                    </span>
                </td>
                <td>{{ $character->getSpecImg() }}</td>
                <td>{{ $character->ilvl }}</td>
            </tr>
        @endforeach
    </tbody>
</table>