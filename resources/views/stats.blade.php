@extends('welcome')

@section('content')
    <div class="panel panel-info" id="tab-stats">
        <div class="panel-heading">
            <h4>Stats</h4>
        </div>
        <div class="panel-body" id="panel-stats" >
            <div class="row">
                <div class="col-md-5">
                    <div id="stats-div" style="width: 800px; height: 500px;"></div>
                </div>
                <div class="col-md-3" id="most-deaths"></div>
                <div class="col-md-3" id="most-kills"></div>
            </div>
        </div>
    </div>
@endsection
