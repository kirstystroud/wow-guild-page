@extends('welcome')

@section('content')
    <div class="panel panel-info" id="tab-professions">
        <div class="panel-heading">
            <h4>Professions</h4>
        </div>
        <div class="panel-body">
            <div class="panel-group">
                @foreach($professions as $profession)
                    <div class="panel panel-info profession-panel">
                        <div class="panel-heading">
                            <h4>
                                <span><img class="icon" src="{{ $profession->getIconLocation() }}"> </span>
                                <a data-toggle="collapse" href="#profession-row-{{ $profession->id }}">
                                    {{ $profession->name }}
                                </a>
                            </h4>
                        </div>
                        <div id="profession-row-{{ $profession->id }}" class="panel-body panel-collapse collapse">
                            <table class="table">
                                <thead>
                                    <th>Name</th>
                                    <th>Skill</th>
                                </thead>
                                @foreach($profession->getCharacterData() as $char)
                                    <tr class="members-tr-{{ $char->class }} char-{{ $char->id }}">
                                        <td>{{ $char->name }} <span>({{ $char->level }})</span></td>
                                        <td>{{ $char->skill }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
