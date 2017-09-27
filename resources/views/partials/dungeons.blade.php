<div class="panel-group">
    @foreach($dungeons as $dungeon)
        <div class="panel dungeon-panel-pending dungeon-panel" id="dungeon-panel-{{ $dungeon->id }}">
            <div class="panel-heading">
                <h4>
                    <a data-toggle="collapse" href="#dungeon-row-{{ $dungeon->id }}">
                        {{ $dungeon->getHeading() }}
                    </a>
                    <p class="text-right pull-right">{{ $dungeon->location }}</p>
                </h4>
            </div>
            <div id="dungeon-row-{{ $dungeon->id }}" class="panel-body panel-collapse collapse">
                Loading ...
            </div>
        </div>
    @endforeach
</div>
