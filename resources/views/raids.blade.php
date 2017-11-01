@extends('welcome')

@section('content')
    <div class="panel panel-info" id="tab-raids">
        <div class="panel-heading">
            <h4>
                Raid Progress
                <p class="pull-right text-right">
                    <select id="dungeon-char-select">
                        <option value="0">Select Character ...</option>
                        @foreach($characters as $char)
                            <option value="{{ $char->id }}">{{ $char->name }}</option>
                        @endforeach
                   </select>
                </p>
            </h4>
        </div>
        <div class="panel-body" id="raids-panel-group">
            Loading ...
        </div>
    </div>
@endsection
