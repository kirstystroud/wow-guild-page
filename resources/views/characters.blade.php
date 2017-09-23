@extends('welcome')

@section('content')
    <div id="tab-chars" class="tab-pane fade in active">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>Characters</h4>
            </div>
            <div class="panel-body" id="guild-members-list">
                Loading ...
            </div>
        </div>
    </div>
@endsection
