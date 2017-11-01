@if(isset($class))
    <a href="{{ $character->getLinkAddr() }}" target="_blank" class="{{ $class }}">
@else
    <a href="{{ $character->getLinkAddr() }}" target="_blank">
@endif

@if(isset($title))
    {{ $char->getTitle() }}
@else
    {{ $character->name }}
@endif
</a> 

@if( !isset($omitLevel) )
    <span>({{ $character->level }})</span>
@endif
