<label for="{{ $key }}" class="checkbox-inline">
    @if(isset($filters[$key]) && ($filters[$key]) && ($filters[$key] != 'false'))
        <input type="checkbox" id="{{ $key }}" checked="checked">{{ $label }}</input>
    @else
        <input type="checkbox" id="{{ $key }}">{{ $label }}</input>
    @endif
</label>
