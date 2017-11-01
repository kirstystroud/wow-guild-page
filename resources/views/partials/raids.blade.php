<div class="panel-group">
    @foreach($raids as $raid)
        <div class="panel dungeon-panel-pending dungeon-panel" id="raid-panel-{{ $raid->id }}">
            <div class="panel-heading">
                <h4>
                    <a data-toggle="collapse" href="#raid-row-{{ $raid->id }}">
                        {{ $raid->getHeading() }}
                    </a>
                    <p class="text-right pull-right">{{ $raid->location }}</p>
                </h4>
            </div>
            <div id="raid-row-{{ $raid->id }}" class="panel-body panel-collapse collapse">
                Loading ...
            </div>
        </div>
    @endforeach
</div>
