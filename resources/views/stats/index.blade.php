@extends('welcome')

@section('content')
    <div class="panel panel-main" id="tab-stats">
        <div class="panel-heading">
            <h4>Stats</h4>
        </div>
        <div class="panel-body" id="panel-stats" >
            <div class="row">
                <div class="col-md-6">
                    <div id="stats-div" style="width:100%;min-width:500px;height:400px;">
                        Loading class levels ...
                    </div>
                    <div id="stats-pie-div" style="width:100%;min-width:500px;height:500px">
                        Loading class kills ...
                    </div>
                </div>
                <div class="col-md-3" id="most-deaths">
                    Loading character deaths ...
                </div>
                <div class="col-md-3" id="most-kills">
                    Loading character kills ...
                </div>
            </div>
            <div class="row">
                <div class="col-md-3" id="dungeons-entered">
                    Loading character dungeons ...
                </div>
                <div class="col-md-3" id="pvp-kills">
                    Loading pvp kills ...
                </div>
                <div class="col-md-6">
                    <div id="stats-pie-quests-div" style="width:100%;min-width:500px;height:500px">
                        Loading class quests ...
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
