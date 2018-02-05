@extends('welcome')

@section('content')
    <div class="panel panel-main" id="tab-professions">
        <div class="panel-heading">
            <h4>Professions</h4>
        </div>
        <div class="panel-body">
            @if ( CharacterRecipe::count() )
                <div>
                    <button class="btn btn-search" data-toggle="modal" data-target="#recipe-search-modal">Recipe Search</button>
                    @include('professions.partials.recipe-search')
                </div>
            @endif
            <br>
            <div class="panel-group">
                @foreach($professions as $profession)
                    <div class="panel panel-info profession-panel wow-panel-grouped">
                        <div class="panel-heading">
                            <h4>
                                <span><img class="icon" src="{{ $profession->getIconLocation() }}"> </span>
                                <a data-toggle="collapse" href="#profession-row-{{ $profession->id }}">
                                    {{ $profession->name }}
                                </a>
                                <p class="pull-right">(Max Skill: {{ $profession->getMaxSkill() }})</p>
                            </h4>
                        </div>
                        <div id="profession-row-{{ $profession->id }}" class="panel-body panel-collapse collapse">
                            <table class="table">
                                <thead>
                                    <th>Name</th>
                                    <th>Skill</th>
                                </thead>
                                @foreach($profession->getCharacterData() as $char)
                                    <tr>
                                        <td>@include('partials.character-link', [ 'character' => $char ])</td>
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
