<th id="th-{{ $key }}">
    <a href="#" class="table-sort" sort="{{ $key }}">
        {{ $label }}
        @if(isset($filters['sort'][$key]))
            @if( $filters['sort'][$key] == 'asc' )
                <span class="glyphicon glyphicon-chevron-up" sort="asc"></span>
            @elseif ($filters['sort'][$key] == 'desc')
                <span class="glyphicon glyphicon-chevron-down" sort="desc"></span>
            @endif
        @endif
    </a>
</th>
