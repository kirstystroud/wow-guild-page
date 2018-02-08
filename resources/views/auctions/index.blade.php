@extends('welcome')

@section('content')
    <div id="tab-auctions" class="tab-pane fade in active">
        <div class="panel panel-main">
            <div class="panel-heading">
                <h4>
                    Auctions
                    <p class="pull-right text-right">
                    <select id="auction-char-select">
                        <option value="0">Select Character ...</option>
                        @foreach($characters as $char)
                            <option value="{{ $char->id }}">{{ $char->name }}</option>
                        @endforeach
                   </select>
                </p>
                </h4>
            </div>
            <div class="panel-body" id="auctions-panel">
                Loading ...
            </div>
        </div>
    </div>
@endsection
