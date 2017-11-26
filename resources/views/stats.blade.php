@extends('welcome')

@section('content')
    <div class="panel panel-main" id="tab-stats">
        <div class="panel-heading">
            <h4>Stats</h4>
        </div>
        <div class="panel-body" id="panel-stats" >
            <div class="row">
                <div class="col-md-6">
                    <div id="stats-div" style="width:100%;min-width:500px;height:400px;"></div>
                    <div id="stats-pie-div" style="width:100%;min-width:500px;height:400px"></div>
                </div>
                <div class="col-md-3" id="most-deaths"></div>
                <div class="col-md-3" id="most-kills"></div>
            </div>
        </div>
    </div>
@endsection
