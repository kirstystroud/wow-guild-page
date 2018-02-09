<th id="th-{{ $key }}">
    <a href="#" class="table-sort" sort="{{ $key }}">
        {{ $label }}
        @if(isset($filters['sort'][$key]))
            @if( $filters['sort'][$key] == 'asc' )
                <span class="glyphicon glyphicon-chevron-up wow-sort" sort="asc" key="{{ $key }}"></span>
            @elseif ($filters['sort'][$key] == 'desc')
                <span class="glyphicon glyphicon-chevron-down wow-sort" sort="desc" key="{{ $key }}"></span>
            @endif
        @endif
    </a>
</th>
