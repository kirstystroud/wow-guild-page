<div class="form-group">
    <label for="{{ $key }}">{{ $label }}</label>
    <select id="{{ $key }}" class="form-control" name="{{ $key }}">
        <option value="{{ isset($default) ? $default : 0 }}">Select ...</option>
        @foreach($data as $i => $d)
            @if(isset($filters[$key]) && ($filters[$key] == (is_object($d) ? $d->id : $i)))
                <option value="{{ is_object($d) ? $d->id : $i }}" selected="selected">{{ is_object($d) ? $d->name : $d }}</option>
            @else
                <option value="{{ is_object($d) ? $d->id : $i }}">{{ is_object($d) ? $d->name : $d }}</option>
            @endif
        @endforeach
    </select>
</div>
