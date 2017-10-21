@extends('welcome')

@section('content')
    <div class="panel panel-info" id="tab-reputations">
        <div class="panel-heading">
            <h4>
                Factions
                <p class="pull-right text-right">
                    <select id="reputation-char-select">
                        <option value="0">Select Character ...</option>
                        @foreach($characters as $char)
                            <option value="{{ $char->id }}">{{ $char->name }}</option>
                        @endforeach
                   </select>
                </p>
            </h4>
        </div>
        <div class="panel-body" id="reputations-panel-group">
            Loading ...
        </div>
    </div>
@endsection
