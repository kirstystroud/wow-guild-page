<div class="panel-group">
    @foreach($reputation as $r)
        <div class="panel reputation-panel-pending reputation-panel" id="reputation-panel-{{ $r->id }}">
            <div class="panel-heading">
                <h4>
                    <a data-toggle="collapse" href="#reputation-row-{{ $r->id }}">
                        {{ $r->name }}
                    </a>
                </h4>
            </div>
            <div id="reputation-row-{{ $r->id }}" class="panel-body panel-collapse collapse">
                Loading ...
            </div>
        </div>
    @endforeach
</div>
