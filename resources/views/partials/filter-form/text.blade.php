<div class="form-group">
    <label for="{{ $key }}">{{ $label }}</label>
    @if(isset($filters) && isset($filters[$key]) && $filters[$key])
        <input type="text" id="{{ $key }}" class="form-control" value="{{ $filters[$key] }}"></input>
    @else
        <input type="text" id="{{ $key }}" class="form-control"></input>
    @endif
</div>
